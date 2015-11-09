$(function() {
  //Autocomplete
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
  //Filter
  $( "#slider-range" ).slider({
    range: true,
    min: parseInt($('#min').val()),
    max: parseInt($('#max').val()),
    values: [parseInt($('#cur-min').val()), parseInt($('#cur-max').val())],
    slide: function( event, ui ) {
      $('#min-price').val(ui.values[0]);
      $('#max-price').val(ui.values[1]);
    }
  });
  //Accordion
  /*$('#filters').accordion();
  $('#filters').accordion("option", "icons", {
    'header': 'glyphicon glyphicon-chevron-down', 'activeHeader': 'glyphicon glyphicon-chevron-up'
  });*/
});
