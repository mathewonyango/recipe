<script src="//code.jquery.com/jquery.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<style>
    .show_error {
        background-color: red !important;
        color: white;
    }
</style>

@if (count($errors) > 0)
    <div class="show_error">
        <strong>Whoops!</strong><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>
                    <h6 class="error">{{ $error }}</h6>
                </li>
            @endforeach
        </ul>
    </div>
@endif

@include('flash::message')
