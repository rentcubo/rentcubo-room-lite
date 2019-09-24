<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>

<script type="text/javascript">

    var defaultImage = "{{ asset('placeholder.png') }}";
    var chatBox = document.getElementById('chat-box');
    var chatInput = document.getElementById('chat-input');
    var chatSend = document.getElementById('chat-send');

    var messageTemplate = function(data) {

        var messageTemplate = '';

        // Provider to user Message

        if(data.type == 'pu') {         
        	messageTemplate += '<div class="chat-left">';
            messageTemplate += '<p class="chat-msg1">'+data.message+'</p>';
            messageTemplate += '</div>';
            messageTemplate += '<div class="clear-both"></div>';
        } else {
            messageTemplate += '<div class="chat-right">';   
            messageTemplate += '<p class="chat-msg2">'+data.message+'</p>';
            messageTemplate += '</div>';
            messageTemplate += '<div class="clear-both"></div>';
        }


        return messageTemplate;
    }

    chatSockets = function () {
        this.socket = undefined;
       
    }

    chatSockets.prototype.initialize = function() {

        this.socket = io('{{ Setting::get("chat_socket_url") }}', { 
                query: "commonid=user_id_1_provider_id_1_host_id_0" 
            });

        console.log('Initalize');

        this.socket.on('connected', function (data) {
            socketState = true;
            chatInput.enable();
            console.log('Connected :: '+data);
        });

        this.socket.on('message', function (data) {

            alert("djdjdjjdjdj");

            if(data.message){

                $('#chat-box').append(messageTemplate(data));

                $(".chat-content").scrollTop($("#chat-box").height());
            }
        });

        this.socket.on('disconnect', function (data) {
            socketState = false;
            chatInput.disable();
            console.log('Disconnected from server');
        });
    }

    chatSockets.prototype.sendMessage = function(data) {

        data = {};
        data.type = 'up';
        data.message = text;
        data.user_id = "{{$user_id}}";
        data.provider_id = "{{$provider_id}}";
        data.data_type = 'TEXT';
        data.status = 'sent';

        this.socket.emit('message', data); 
    }

    socketClient = new chatSockets();
    socketClient.initialize();

    chatInput.enable = function() {
        this.disabled = false;
    };

    chatInput.clear = function() {
        this.value = "";
    };

    chatInput.disable = function() {
        this.disabled = true;
    };

    chatInput.addEventListener("keyup", function (e) {
        if (e.which == 13) {
            sendMessage(chatInput);
            return false;
        }
    });

    chatSend.addEventListener('click', function(event) {
        event.preventDefault();
        sendMessage(chatInput);
    });
    

    function sendMessage(input) {
        text = input.value.trim();
        if(socketState && text != '') {

            message = {};
            message.type = 'up';
            message.message = text;

            socketClient.sendMessage(text);

            $('#chat-box').append(messageTemplate(message));

            chatInput.clear();
            
            $(".chat-content").scrollTop($("#chat-box").height());


        }
    }

    <?php /** 

    // $.get('{{ route("user.requests.chat") }}', {

    //     request_id: '{{$request_id}}',provider_id: '{{ $provider->id }}',

    // }).done(function(response) {
        
    //     for (var i = (response.length - 10 >= 0 ? response.length - 10 : 0); i < response.length; i++) {
           
    //         $('#chat-box').append(messageTemplate(response[i]));

    //         $(".chat-content").scrollTop($("#chat-box").height());

           
    //     }
    // })
    // .fail(function(response) {
    //     // console.log(response);
    // })
    // .always(function(response) {
    //     // console.log(response);
    // });

    */ ?>

    $("#chat-input").keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    });

</script>