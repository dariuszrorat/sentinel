<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Kohana Sentinel</title>
    <style>
      body
      {
          width: 1000px;
          margin: auto;    
      }       
      header
      {
          background: #d5cea6; /* Old browsers */
          background: -moz-linear-gradient(top, #d5cea6 0%, #b7ad70 100%); /* FF3.6-15 */
          background: -webkit-linear-gradient(top, #d5cea6 0%,#b7ad70 100%); /* Chrome10-25,Safari5.1-6 */
          background: linear-gradient(to bottom, #d5cea6 0%,#b7ad70 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
          filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d5cea6', endColorstr='#b7ad70',GradientType=0 );          
          height: 30px;
          padding-top: 5px;
          padding-bottom:15px;  
      }
      header h1
      {
          text-align: center;
          margin-top: 0px;
      }
      .menu
      {
          float: left;
          width: 200px;          
          height: 550px;          
          background: #d2dfed; /* Old browsers */
          background: -moz-linear-gradient(left, #d2dfed 0%, #a6c0e3 0%, #c8d7eb 26%, #bed0ea 51%, #afc7e8 62%, #bad0ef 75%, #99b5db 88%, #799bc8 100%); /* FF3.6-15 */
          background: -webkit-linear-gradient(left, #d2dfed 0%,#a6c0e3 0%,#c8d7eb 26%,#bed0ea 51%,#afc7e8 62%,#bad0ef 75%,#99b5db 88%,#799bc8 100%); /* Chrome10-25,Safari5.1-6 */
          background: linear-gradient(to right, #d2dfed 0%,#a6c0e3 0%,#c8d7eb 26%,#bed0ea 51%,#afc7e8 62%,#bad0ef 75%,#99b5db 88%,#799bc8 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
          filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d2dfed', endColorstr='#799bc8',GradientType=1 );          
      }
      .menu a
      {
          display: block;
          width:190px;
          height: 30px;
          background: #f2f5f6; /* Old browsers */
          background: -moz-linear-gradient(left, #f2f5f6 0%, #c8d7dc 100%); /* FF3.6-15 */
          background: -webkit-linear-gradient(left, #f2f5f6 0%,#c8d7dc 100%); /* Chrome10-25,Safari5.1-6 */
          background: linear-gradient(to right, #f2f5f6 0%,#c8d7dc 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
          filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f5f6', endColorstr='#c8d7dc',GradientType=1 );          
          text-align: center;
          padding-top: 10px;
          text-decoration: none;
          font-weight: bold;
          color: black;
          border: 3px solid black;
          border-radius: 5px;          
          margin-left: 2px;
          margin-top: 5px;
      }
      .menu a:hover
      {
          background: #febbbb; /* Old browsers */
          background: -moz-linear-gradient(left, #febbbb 0%, #fe9090 45%, #ff5c5c 100%); /* FF3.6-15 */
          background: -webkit-linear-gradient(left, #febbbb 0%,#fe9090 45%,#ff5c5c 100%); /* Chrome10-25,Safari5.1-6 */
          background: linear-gradient(to right, #febbbb 0%,#fe9090 45%,#ff5c5c 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
          filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#febbbb', endColorstr='#ff5c5c',GradientType=1 ); 
      }
      .results
      {
          float: left;
          border: 1px solid #000;
          width: 798px;
          height: 548px;
          position: relative;
      }
      .message
      {
          width: 200px;
          height: 100px;
          border: 1px solid #aaa;
          background: #afa;
          border-radius: 5px;
          position: relative;  
          left: 50%;
          top: 50%;
          margin-left: -100px;
          margin-top: -100px;
          display: none;
      }
      .message-info
      {
          font-weight: bold;
          text-align: center;
          margin-left: 10px;
          margin-top: 40px;
          display: block;
      }
      #result-area 
      {
          font: 12px "Lucida Grande", Sans-Serif;
          height: 470px;
          overflow: auto;
          padding: 10px;
          background: white;
          word-wrap: break-word;
          width: 770px;
      }
      #result-area p 
      {
          padding: 0px 0;
          border-bottom: 1px solid #ddd;
      }
      .operations
      {
          float: right;
      }  
      .operation
      {
          float: left;
          display: inline block;
          width: 50px;
          text-align: center;
      }
      .result-title
      {
          text-align: center;
      }
      footer
      {
          background: #f2f5f6; /* Old browsers */
          background: -moz-linear-gradient(top, #f2f5f6 0%, #c8d7dc 100%); /* FF3.6-15 */
          background: -webkit-linear-gradient(top, #f2f5f6 0%,#c8d7dc 100%); /* Chrome10-25,Safari5.1-6 */
          background: linear-gradient(to bottom, #f2f5f6 0%,#c8d7dc 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
          filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f5f6', endColorstr='#c8d7dc',GradientType=0 );                    
          height: 50px;
          clear: both;
      }
    </style>
  </head>
  <body>  
    <header>
    <H1>Antivirus sentinel module</H1>
    </header>
    <div class="menu">
        <a href="javascript:registerFiles()">Register files</a>
        <a href="javascript:findUnregistered()">Find unregistered</a>
        <a href="javascript:findModified()">Find modified</a>
        <a href="javascript:findDeleted()">Find deleted</a>
        <a href="javascript:createBackup()">Backup files</a>
    </div>
    <div class="results" id="result">
        <div class="message" id="message">
            <span class="message-info" id="message-info">Updating checksums...</span>
        </div>
        <H4 class="result-title" id="result-title"></H4>
        <div id="result-area"></div>
    </div>
    <footer>
    </footer>
    <script
	    src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    </script>
    <script>
        var lock = false;

        function registerFiles()
        {
            if (lock == true) return;
            lock = true;
            $('#message-info').html("Registering files...");
            $('#message').show();
            $('#result-area').html("");
            $('#result-title').html("");
            $.ajax({
                type: "GET",
                url: "/sentinel/register",
                data: {},
                dataType: "json",
                success: function (data)
                {
                    $('#message').hide();
                    $('#result-title').html('List of registered files');
                    for (var i=0; i < data.length; i++)
                    {
                        $('#result-area').append($("<p><B>File: </B>" + data[i].file + "<BR><B>Checksum: </B>" + data[i].checksum + "</p>")); 
                    }
                    lock = false;
                }
            });
            
        }         
        
        function findModified()
        {
            if (lock == true) return;
            lock = true;

            $('#message-info').html("Search for modified files...");
            $('#message').show();
            $('#result-area').html("");
            $('#result-title').html("");
            $.ajax({
                type: "GET",
                url: "/sentinel/modified",
                data: {},
                dataType: "json",
                success: function (data)
                {
                    if (data.length == 0)
                    {
                        $('#message-info').html("No modified or suspicious files detected.");                        
                        setTimeout(function () 
                        {
                            $('#message').hide();
                            lock = false;
                        }, 4000);                        
                    }
                    else
                    {
                        $('#message').hide();
                        $('#result-title').html('List of modified files');
                        for (var i=0; i < data.length; i++)
                        {
                            $('#result-area').append($("<p><B>File: </B>" + data[i].file 
                            + "<BR><B>Original checksum: </B>" + data[i].original_checksum 
                            + "<BR><B>New checksum: </B>" + data[i].new_checksum                        
                            + '<span class="operations">'
                            + '<a href="javascript:updateChecksum(' + data[i].index + ')" class="operation">Update</a>'
                            + '<a href="javascript:repairFile(' + data[i].index + ')" class="operation">Repair</a>'
                            + '<a href="javascript:deleteFile(' + data[i].index + ')" class="operation">Delete</a>'
                            + '</span>'
                            + "</p>")); 
                        }
                        lock = false;
                    }

                }
            });            
        }      

        function findUnregistered()
        {
            if (lock == true) return;
            lock = true;

            $('#message-info').html("Search for unregistered files...");
            $('#message').show();
            $('#result-area').html("");
            $('#result-title').html("");
            $.ajax({
                type: "GET",
                url: "/sentinel/unregistered",
                data: {},
                dataType: "json",
                success: function (data)
                {
                    if (data.length == 0)
                    {
                        $('#message-info').html("No unregistered or suspicious files detected.");                        
                        setTimeout(function () 
                        {
                            $('#message').hide();
                            lock = false;
                        }, 4000);                        
                    }
                    else
                    {
                        $('#message').hide();
                        $('#result-title').html('List of unregistered files');
                        for (var i=0; i < data.length; i++)
                        {
                            $('#result-area').append($("<p><B>File: </B>" + data[i].file 
                            + "<BR><B>Checksum: </B>" + data[i].checksum 
                            + '<span class="operations">'
                            + '<a href="javascript:registerFile(' + data[i].index + ')" class="operation">Register</a>'
                            + '<a href="javascript:deleteFile(' + data[i].index + ')" class="operation">Delete</a>'
                            + '</span>'
                            + "</p>")); 
                        }
                        lock = false;
                    }
                }
            });            
        }      

        function findDeleted()
        {
            if (lock == true) return;
            lock = true;

            $('#message-info').html("Search for deleted files...");
            $('#message').show();
            $('#result-area').html("");
            $('#result-title').html("");
            $.ajax({
                type: "GET",
                url: "/sentinel/deleted",
                data: {},
                dataType: "json",
                success: function (data)
                {
                    if (data.length == 0)
                    {
                        $('#message-info').html("No deleted files detected.");                        
                        setTimeout(function () 
                        {
                            $('#message').hide();
                            lock = false;
                        }, 4000);                        
                    }
                    else
                    {
                        $('#message').hide();
                        $('#result-title').html('List of deleted files');
                        for (var i=0; i < data.length; i++)
                        {
                            $('#result-area').append($("<p><B>File: </B>" + data[i].file 
                            + "<BR><B>Checksum: </B>" + data[i].checksum 
                            + '<span class="operations">'
                            + '<a href="javascript:restoreFile(' + data[i].index + ')" class="operation">Restore</a>'
                            + '<a href="javascript:forgetDeleted(' + data[i].index + ')" class="operation">Forget</a>'
                            + '</span>'
                            + "</p>")); 
                        }
                        lock = false;
                    }
                }
            });            
        }      
        
        function createBackup()
        {
            if (lock == true) return;
            lock = true;

            $('#message-info').html("Backup files...");
            $('#message').show();
            $('#result-area').html("");
            $('#result-title').html("");
            $.ajax({
                type: "GET",
                url: "/sentinel/backup",
                data: {},
                dataType: "json",
                success: function (data)
                {
                    if (data.result == true)
                    {
                        $('#message-info').html("Backup was successfully created");
                    }
                    else
                    {
                        $('#message-info').html("Backup was not created");
                    }
                                                
                    setTimeout(function () 
                    {
                        $('#message').hide();
                        lock = false;
                    }, 4000);
                }                
            });            
        }         
            
        function updateChecksum(id)
        {
            if (lock == true) return;
            lock = true;

            $('#message-info').html("Updating checksum...");
            $('#message').show();            
             
            $.ajax({
                type: "POST",
                url: "/sentinel/updateone",
                data: {'id': id},
                dataType: "json",
                success: function (data)
                {                    
                    if (data.result == true)
                    {
                        $('#message-info').html("Checksum was successfully updated");
                    }
                    else
                    {
                        $('#message-info').html("Checksum was not updated");
                    }
                                                
                    setTimeout(function () 
                    {
                        $('#message').hide();
                        lock = false;
                    }, 4000);
                }
            });            
        }        
    </script>
  </body>
</html>
