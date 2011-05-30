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
           jQuery('#showinfo').bind('click', function() {
                                        jQuery('#showinfo_result_pageurl').text('Loading...');
                                        jQuery('#showinfo_result_title').text('Loading...');
                                        jQuery('#showinfo_result_referer').text('Loading...');
                                        jQuery('#showinfo_result_imageurls').text('Loading...');

                                        if (jQuery('#showinfo_result').css('display') != 'none') {
                                            jQuery('#showinfo_result').hide();
                                        }
                                        jQuery('#showinfo_result').slideDown('slow');

                                        jQuery.getJSON('api/get',
                                                       {
                                                           url: jQuery('#url').val()
                                                       },
                                                       function(data) {
                                                           jQuery('#showinfo_result_pageurl').text(data.pageurl);
                                                           jQuery('#showinfo_result_title').text(data.title);
                                                           jQuery('#showinfo_result_referer').text(data.referer);
                                                           var imageurls = '';
                                                           var length = data.imageurls.length;
                                                           for (var i = 0; i < length; ++i) {
                                                               imageurls += data.imageurls[i] + "\n";
                                                           }
                                                           jQuery('#showinfo_result_imageurls').text(imageurls);
                                                       });
                                    });
       });
