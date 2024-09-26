 {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="crossorigin="anonymous"></script>
 <script>
     document.addEventListener("contextmenu", function(event){
     event.preventDefault();
     alert('Right Click is Disabled');

         $(window).on('keydown',function(event)
         {
         if(event.keyCode==123)
         {
             alert('F12 Disabled');
             return false;
         }
         else if(event.ctrlKey && event.shiftKey && event.keyCode==73)
         {
             alert('Operation not allowed')
             return false;
         }

         else if(event.ctrlKey && event.keyCode==73)
         {
             alert('Operation not allowed')
             return false;
         }
     });

 }, false);
 </script> --}}


 <script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            document.getElementById('loader').style.display = 'none';
        }, 1500); // Hide loader after 1.5 seconds
    });
</script>


<script src="{{ URL::asset('assets/js/vendor.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@yield('script')
<script src="{{ URL::asset('assets/js/app.min.js') }}"></script>
<!-- In the <head> section -->




@yield('script-bottom')
