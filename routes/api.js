var express = require('express'),
    router = express.Router(),
    soap = require('soap'),
    request = require('request'),
    requestretry = require('requestretry'),
    cheerio = require('cheerio'),
    async = require('async'),
    constants = require('../config/constants');


function retryStrategy(err,response){
    return err || response.statusCode != 200;
}

/* GET home page. */
router.get('/', function(req, res, next) {
    res.render('index', { title: 'Puntos | Comunio UK' });
});

//GET ComunioUK user ID
router.get('/id/:user', function(req, res, next) {

    var user = {name: req.params.user};

    soap.createClient(constants.COMUNIO_WSDL_URI, function(err, client) {
        client.getuserid(user, function(err, result) {
            if (err) {
                return next(err);
            }
            res.json(result);
        });
    });
});

//GET user current lineup
router.get('/lineup/:userid', function(req, res, next) {
    requestretry({
        url: constants.COMUNIO_LINEUP_URI + req.params.userid,
        json: true,
        retryStrategy: retryStrategy
    }, function(error, response, html){
        if (!error) {
            var $ = cheerio.load(html);
            var lineup = [],
                players = $('.name_cont');

            async.eachSeries(players, function(name, callback){
                lineup.push($(name).text());
                callback();
            }, function (err){
                if (err) { console.log(err); throw err; }
                console.log("Lineup",lineup);
                res.json(lineup);
            });
        } else {
            return next(err);
        }
    });
});

//GET player info [name, link,team] from whoscored.com searching result
router.get('/info/:player', function(req, res, next) {
    requestretry({
        url: constants.WHOSCORED_SEARCH_URI + req.params.player,
        headers: {"Cache-Control" : "no-cache"},
        json: true,
        retryStrategy: retryStrategy
    }, function(error, response, html){
      if (!error){
          try{
              var $ = cheerio.load(html),
                  playersInfo = [],
                  searchJSON = {name: "", link: "", team: ""},
                  searchRowsResult = $('.search-result tr td:nth-child(1)');

              async.eachSeries(searchRowsResult, function(row, callback){
                  searchJSON.name = $(row).children('a').text();
                  searchJSON.link = constants.WHOSCORED_URI + $(row).children('a').attr('href');
                  searchJSON.team = $(row).next('td').children('a').text();
                  if (searchJSON.team != "") {
                      if (constants.TEAMS.indexOf(searchJSON.team) != -1) {
                          playersInfo.push(searchJSON);
                      }
                  }
                  searchJSON = {name: "", link: "", team: ""};
                  callback();
              }, function (err){
                  if (err) { console.log(err); throw err; }
                  console.log("Playersinfo:",playersInfo);
                  res.json(playersInfo);
              });
          } catch (err){
              next(err);
          }
      } else {
          return next(err);
      }
  });
});


//GET player last rating
router.get('/rating/last', function(req, res, next) {
    requestretry({
      url: req.query.playerurl,
      headers: {"Cache-Control" : "no-cache"},
      json: true,
      retryStrategy: retryStrategy
    }, function(error, response, html){
      if (!error){
          try{
              var $ = cheerio.load(html),
                  jsonRating = {rating: ""};

              if ($('.tournament-link').last().text()==='EPL'){
                  jsonRating.rating = $('.fixture .rating').last().text();
              } else {
                  jsonRating.rating = "6.0";
              }
              console.log(jsonRating);
              res.json(jsonRating);

          } catch (err) {
              return next(err);
          }
      } else {
          return next(err);
      }
    });
});

module.exports = router;
