    // SideNav Initialization
    
    $(".button-collapse").sideNav();      

    var container = document.querySelector('.custom-scrollbar');
    var ps = new PerfectScrollbar(container, {
      wheelSpeed: 2,
      wheelPropagation: true,
      minScrollbarLength: 20
    });

    $('.button-collapse').click(function() {
      $('#slide-out').show();
      $('#custom-scrollbar').css("transform","translateX(0px)");
      $('#sidenav-overlay').show();
    });

      function closeNav() {
        $('#sidenav-overlay').trigger('click');
      }

// <!++++++++++++++++++++++++++++++++++++++++++++==


$('body').on('click', '#sidebar', function (event)
   {
      $.get('/orders/batched_orders/visit_count', function (data) {  
      console.log(data.data);
         $('#batch-count').text(data.data.batch);
         $('#pending-count').text(data.data.pending);
         $('#complete-count').text(data.data.complete);
         $('#skip-count').text(data.data.skip);
         $('#cancel-count').text(data.data.cancel);
         $('#postpone-count').text(data.data.postpone);
         $('#return-count').text(data.data.return);
         $('#undelivered-count').text(data.data.undelivered);
         $('.collapsible-body').css("display","block");
       })
  });
