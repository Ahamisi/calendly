<!DOCTYPE html>
<html>
<head>
  <title>Laravel Fullcalender Add/Update/Delete Event Example Tutorial - Tutsmake.com</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<style>
    .fc-event{
        background-color: #007bff94;
        color: #fff;
        border-color: #007bff94;
    }
</style>
<body>
        <br><br>
  <div class="container">
        <div class="response">

        </div>
      {{-- <div class="response alert alert-dismissible"></div> --}}
      <div id='calendar'></div>  
  </div>
 
 
</body>
</html>
<script>
    $(document).ready(function () {
           
          var SITEURL = "{{url('/')}}";
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $('div .fc-time').html(`<i class="fa fa-calendar-check-o" aria-hidden="true"></i>`);

   
          var calendar = $('#calendar').fullCalendar({
              editable: true,
              events: SITEURL + "/fullcalendareventmaster",
              displayEventTime: true,
              editable: true,
              eventRender: function (event, element, view) {
                  if (event.allDay === 'true') {
                      event.allDay = true;
                  } else {
                      event.allDay = false;
                  }
              },
              selectable: true,
              selectHelper: true,
              select: function (start, end, allDay) {
                  var title = prompt('Event Title:');
   
                  if (title) {
                      var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                      var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
   
                      $.ajax({
                          url: SITEURL + "/fullcalendareventmaster/create",
                          data: 'title=' + title + '&start=' + start + '&end=' + end,
                          type: "POST",
                          success: function (data) {
                              displayMessage("Added Successfully");
                          }
                      });
                      calendar.fullCalendar('renderEvent',
                              {
                                  title: title,
                                  start: start,
                                  end: end,
                                  allDay: allDay
                              },
                      true
                              );
                  }
                  calendar.fullCalendar('unselect');
              },
               
              eventDrop: function (event, delta) {
                          var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                          var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                          $.ajax({
                              url: SITEURL + '/fullcalendareventmaster/update',
                              data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                              type: "POST",
                              success: function (response) {
                                  displayMessage("Updated Successfully");
                              }
                          });
                      },
              eventClick: function (event) {
                  var deleteMsg = confirm("Do you really want to delete?");
                  if (deleteMsg) {
                      $.ajax({
                          type: "POST",
                          url: SITEURL + '/fullcalendareventmaster/delete',
                          data: "&id=" + event.id,
                          success: function (response) {
                              if(parseInt(response) > 0) {
                                  $('#calendar').fullCalendar('removeEvents', event.id);
                                  displayMessage("Deleted Successfully");
                              }
                          }
                      });
                  }
              }
   
          });
    });
   
    function displayMessage(message) {
                $(".response").html(`<p class="alert alert-info alert-dismissible">${message}</p>`);
      setInterval(function() { $(".success").fadeOut(); }, 1000);
    }
    window.addEventListener('DOMContentLoaded', ()=>{
        $('.fc-time').html(`<i class="fa fa-calendar-check-o" aria-hidden="true"></i>`)
    });

     

  </script>