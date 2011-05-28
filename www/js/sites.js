// -*- Mode: js2; indent-tabs-mode: nil; -*-

jQuery(function() {
           jQuery.getJSON('api/sites', function(data) {
                              var sites = [];
                              var length = data['sites'].length;
                              for (var i = 0; i < length; ++i) {
                                  jQuery('<div/>', {
                                             html: data['sites'][i]
                                         }).appendTo('#sites');
                              }
                          });
       });
