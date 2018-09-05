(function () {

     'use strict';

     Vue.component('facebook-posts', {
          template: '#template-facebook-posts',
          data: function data() {
               return {
                    data: []
               };
          },
          methods: {
               // Save voted rating into database
               getFacebookData: function () {
                    var self = this;
                    jQuery.ajax({
                         data: {
                              action: 'get_facebook_data'
                         },
                         type: 'POST',
                         dataType: 'json',
                         url: facebookposts.url,
                         success: function (data) {
                              //console.log(data);
                              self.data = data.data;
                         },
                         error: function (error) {
                              console.log(error);
                         }
                    });
               },
               getHumanTime: function (created_time) {
                    var date = new Date(created_time);
                    var options = {
                         day: 'numeric',
                         month: 'long',
                         year: 'numeric'
                    };
                    // Modifies Wordpress locale, ie. en_US -> en-US
                    var locale = facebookposts.locale.replace('_', '-');
                    return date.toLocaleDateString(locale, options);
               },
               getGregorianTime: function (created_time) {
                    var date = new Date(created_time);
                    return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
               },
               // Format Facebook URL by first splitting ID to get Page and Post ID
               getPostUrl: function (postID) {
                    var exploded = postID.split('_');
                    return 'https://www.facebook.com/' + exploded[0] + '/posts/' + exploded[1];
               },
               scrollToTop: function () {
                    jQuery('html,body').animate({
                         scrollTop: jQuery('#js-facebook-posts').offset().top - 120
                    }, 400);
               },
               getImageUrlIfExists(post) {
                    // If using Lodash
                    // return _.get(post, 'attachments.data[0].media.image.src', null);
                    // Otherwise it is a little nasty
                    if (post && post.attachments && post.attachments.data && post.attachments.data[0] && post.attachments.data[0].media && post.attachments.data[0].media.image && post.attachments.data[0].media.image.src) {
                         return post.attachments.data[0].media.image.src;
                    }

               }
          },
          mounted: function () {
               this.getFacebookData();
          }
     });

     new Vue({
          el: '#js-facebook-posts'
     });

})();