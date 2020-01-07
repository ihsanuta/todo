<html>
    <head>
        <title>ToDo List</title>  
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>  
    </head>
    <body>
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-4"><input type="text" name="in_todo" id="in_todo" onkeyup="todokeyup()" class="form-control"></div>
                <div class="col-md-4"><button type="button" class="btn btn-primary" id="add_todo">Add Todo</button></div>
            </div>
            <div id="content"></div>
            <br>
            <div id="list_todo">
                @foreach($task as $t)
                    <div class="checkbox" id="cont_{{$t->id}}"><label id="task_{{$t->id}}"><input type="checkbox" value="{{$t->id}}" class="checkbox_lbl"> {{$t->task}}</label></div>
                @endforeach
            </div>
            <button type="button" id="del_todo" class="btn btn-danger">Delete Selected</button>
        </div>
    </div>
    </body>
    <style>
        .wrapper{
            padding : 15px 0 0 15px
        }
    </style>
    <script>
        var iddel = [];
        $(document).ready(function () {
            todokeyup();
        });
        $(document).on("click","#add_todo", function(){
            let txt = $('#in_todo').val();
            $.ajax({
                type : "POST",
                url : '/add_todo',
                data :{
                        "_token": "{{ csrf_token() }}",
                        "todo": txt
                    },
                dataType:'json',
                success : function(res) {
                    if(res.msg == "OK"){
                        let task = '<div class="checkbox" id="cont_'+res.last_id+'"><label id="task_'+res.last_id+'" ><input type="checkbox" class="checkbox_lbl" value="'+res.last_id+'"> '+txt+'</label></div>';
                        $('#list_todo').append(task);
                        $('#in_todo').val("");
                        $("#content").html("Type in a new todo...");
                    }
                }
            });
        });

        $(document).on("click","#del_todo", function(){
            $.ajax({
                type : "POST",
                url : '/delete_todo',
                data :{
                        "_token": "{{ csrf_token() }}",
                        "todo": iddel
                    },
                dataType:'json',
                success : function(res) {
                    if(res.msg == "OK"){
                        for(let i = 0; i < iddel.length; i++){
                            $("#cont_"+iddel[i]).empty();
                        }
                    }
                }
            });
        });

        function todokeyup()
        {
            let todo = $('#in_todo').val();
            if( todo === ""){
                $("#content").html("Type in a new todo...");
            }else{
                $("#content").html("Typing "+ todo);
            }
        }

        $(".checkbox_lbl").change(function() {
            if(this.checked) {
                iddel.push(this.value);
                $("#task_"+this.value).css('text-decoration','line-through');
            }else{
                var idx = $.inArray(this.value, iddel);
                iddel.splice(idx, 1);
                $("#task_"+this.value).css('text-decoration','');
            }
        });
    </script>
</html>