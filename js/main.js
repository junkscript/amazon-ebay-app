$(function() {
  $( "#tags" ).autocomplete({
    max: 10,
    source: function(request, response) {
      $.get("/autocomplete.php?tag=" + $("#tags").val(), function(data) {
        $json = $.parseJSON(data);
        response($.map($json.condition, function(item) {
          if (item.length) {
            return {
              value: item
            };
         }
        }));
      })
    },
    select: function( event, ui ) {
      $( "#tags" ).val(ui.item.value);
      $("#button").click();
      return false;
    },
    search: function() {
      $(this).addClass('open');
    },
    open: function() {
      $(this).removeClass('open');
    }
  })
});
