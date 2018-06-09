window.DSO = window.DSO || {};

DSO.voteDso = (function($, ns, r) {

  ns.BUTTON = 'button.vote';

  ns.KUZZLE_ID = 'input#kuzzle-id';

  ns.STARS = 'span#listStars';

  /**
   *
   */
  ns.init = function() {
    if (0 <  $(ns.KUZZLE_ID).length) {
      ns.vote();
    }
  };


  /**
   * TODO : using kuzzle-sdk JS, not ajax request
   */
  ns.vote = function() {

    let kuzzleId = $(ns.KUZZLE_ID).val();
    $(ns.BUTTON).on('click', function() {
      let $this = $(this);
      let typeVote = $this.data('vote');

      $.ajax({
        method: "POST",
        url: r.generate('dso_upvote'),
        dataType: 'json',
        data: {
          'kuzzleId': kuzzleId,
          'typeVote': typeVote
        }
      }).done(function(data) {
        if (data !== null) {
          let starHtml = JSON.parse(data).html;
          $(ns.STARS).html(starHtml);
          $(ns.BUTTON).prop("disabled",true);
        }
      });

    });

  };

  return ns;
})(jQuery, {}, Routing);