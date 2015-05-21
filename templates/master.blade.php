<!DOCTYPE html>
<html lang="en">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/public/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="/public/css/bootstrap-theme.min.css">


    <!-- Latest compiled and minified JavaScript for Jquery-->
    <script src="/public/js/jquery-1.11.3.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="/public/js/bootstrap.min.js"></script>


    <style type="text/css">
        body {
            background-color:#f8f8f8;
            font-family: 'Quattrocento', serif;
        }
    </style>
    <body>
        <div id="container">
            <div id="todoapp">

                <h1 class="text-center">Workshop 2015 ToDoMVC
                    <p class="lead">PHP & MongoDB & some bootstrap </p> 
                    <p class="label-danger" id="warning"></p>
                </h1> 

                @foreach ($todolist as $todo)
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">
                                <input class="toggle" type="checkbox" id="completed" value="{{isset($todo["id"]) ? $todo["id"] : ""}}" @if (isset($todo["completed"]) && $todo["completed"] ) checked @endif >

                            </span>
                            <input class="edit form-control input-lg" value="{{isset($todo["title"]) ? $todo["title"] : ""}}" >
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-lg destroy" id="deleteTodo" value="{{isset($todo["id"]) ? $todo["id"] : ""}}"><i class="glyphicon glyphicon-remove"></i></button>
                            </span>
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-6 -->
                </div><!-- /row -->
                @endforeach     


                <div class="row">

                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="input-group input-group-lg">
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-lg"><i class="fa fa-chevron-right"></i></button>
                            </span>
                            <input class="form-control input-lg" id="new-todo" placeholder="What needs to be done?" autofocus="">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-lg" id="newTodo"><i class="glyphicon glyphicon-plus"></i></button>
                            </span>
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-6 -->

                </div><!-- /row -->

                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <hr><!-- /divider -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-sm-offset-2  text-center">
                    </div>     
                    <div class="col-sm-4 text-center">
                        <ul class="list-inline" id="filters">
                            <li>
                                <a class="btn btn-lg @if ($buttonSelected == "all" ) btn-info @endif "  href="/api/todos">All</a>
                            </li>
                            <li>
                                <a class="btn btn-lg @if ($buttonSelected == "active" ) btn-info @endif"  href="/api/todos/active">Active <span class="badge badge-info"></span></a>
                            </li>
                            <li>
                                <a class="btn btn-lg @if ($buttonSelected == "completed" ) btn-info @endif"  href="/api/todos/completed">Completed</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-2  text-center">
                        <button class="btn btn-sm btn-success" id="clear-completed" >Clear Competed</button>
                        <button class="btn btn-sm btn-danger" id="kill" >Kill Instance</button>
                    </div>

                </div><!--/ row -->

                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <hr><!-- /divider -->
                    </div>
                </div>




                <hr>

                <h4 class="text-center">
                    Etourneau Gwenn @Pivotal 2015  Ip: {{isset($vcapInfo["ip"]) ? $vcapInfo["ip"] : "unknow" }} Instance index: {{isset($vcapInfo["index"]) ? $vcapInfo["index"] : "unknow"}} 
                </h4>

                <hr>

            </div><!--/ app -->  
        </div><!--/ container -->
        <script type="text/javascript">
            //Insert New Todo
            $('#newTodo').click(function () {
                if (!$.trim($('#new-todo').val()))
                    return;
                $.ajax({
                    url: '/api/todos',
                    data: JSON.stringify({title: $('#new-todo').val(), completed: false }),
                    contentType: "application/json; charset=utf-8",
                    error: function () {
                        $('#warning').html('An error has occurred');
                    },
                    success: function () {
                        location.reload();
                    },
                    type: 'POST'
                });
            });

            //Delete Todo
            $(".btn.btn-default.btn-lg.destroy").on("click", function () {
                $.ajax({
                    url: '/api/todos/' + $(this).val(),
                    data: JSON.stringify({id: $(this).val()}),
                    contentType: "application/json; charset=utf-8",
                    error: function () {
                        $('#warning').html('An error has occurred');
                    },
                    success: function () {
                        location.reload();
                    },
                    type: 'DELETE'
                });
            });

            //Mark as completed
          $(":checkbox").on("click", function () {
                $.ajax({
                    url: '/api/todos/' + $(this).val(),
                    data: JSON.stringify({completed: $(this).is(':checked')}),
                    contentType: "application/json; charset=utf-8",
                    error: function () {
                        $('#warning').html('An error has occurred');
                    },
                    success: function () {
                        location.reload();
                    },
                    type: 'PUT'
                });
            });
            
             //Mark as completed
            $("#clear-completed").on("click", function () {
                $.ajax({
                    url: '/api/todos/completed',
                    data: {},
                    contentType: "application/json; charset=utf-8",
                    error: function () {
                        $('#warning').html('An error has occurred');
                    },
                    success: function () {
                        location.reload();
                    },
                    type: 'DELETE'
                });
            });


             //Mark as completed
            $("#kill").on("click", function () {
                $.ajax({
                    url: '/api/kill',
                    data: {},
                    contentType: "application/json; charset=utf-8",
                    error: function () {
                        $('#warning').html('An error has occurred');
                    },
                    success: function () {
                        location.reload();
                    },
                    type: 'GET'
                });
            });



            //Enter button handle
            $(document).keypress(function (e) {
                if (e.which == 13) {
                    $('#newTodo').click()
                }
            });


        </script>

    </body>
</html>