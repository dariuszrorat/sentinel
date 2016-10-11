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
      .results
      {
          border: 1px solid #000;
          width: 998px;
          height: 548px;
          position: relative;
      }
      #result-area
      {
          font: 12px "Lucida Grande", Sans-Serif;
          height: 460px;
          overflow: auto;
          padding: 10px;
          background: white;
          word-wrap: break-word;
          width: 970px;
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
    <div class="results" id="result">
        <H4 class="result-title" id="result-title"><?= $title ?></H4>
        <div id="result-area"><?= $results ?></div>
    </div>
    <footer>
    </footer>
  </body>
</html>
