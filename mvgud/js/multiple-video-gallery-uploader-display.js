(function($){
  $("#mvgud_instructions p.header").on('click', function(e){
    // Toggles instructions
    $("#mvgud_instructions section").toggle();

    // Changes contents of p tag
    if($("#mvgud_instructions section").is(":visible")) {
      $("#mvgud_instructions p.header").html("MVGUD Instructions ( Hide )")
    } else {
      $("#mvgud_instructions p.header").html("MVGUD Instructions ( Show )")
    }
  })
})(jQuery);
