<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dashboard - SB Admin</title>
        <link href="/css/admin/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
      @include('admin.layouts.header') 
      
      @yield('content') 

      @include('admin.layouts.footer')

      <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
      <script src="/js/scripts.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
      <script src="/assets/js/admin/chart-area-demo.js"></script>
      <script src="/assets/js/admin/chart-bar-demo.js"></script>
      <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
      <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
      <script src="/assets/js/admin/datatables-demo.js"></script>
      <script type="text/javascript" src="{{ asset('vendor/ckeditor5/ckeditor.js') }}"></script>

      <script>
        @if(session()->has('jsAlert'))

          alert("{{ session()->get('jsAlert') }}");

        @endif

        function readURL(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
            $('#image_section').attr('src', e.target.result);  
            }
            
            reader.readAsDataURL(input.files[0]);
          }
        }
          
        $("#images").change(function(){
          readURL(this);
        });

        $('#category_select').change(function(){
          location.href="{{route('admin.item_options')}}?ca_id="+$(this).val();
        });

        var My_editor;

        ClassicEditor
        .create( document.querySelector( '#m_editor' ), {
            ckfinder: {
                uploadUrl: '/admin/Ckfinder/image_upload'
            },
            //plugins: [ Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize ],
            image: {
                // You need to configure the image toolbar, too, so it uses the new style buttons.
                toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight' ],

                styles: [
                    // This option is equal to a situation where no style is applied.
                    'full',

                    // This represents an image aligned to the left.
                    'alignLeft',

                    // This represents an image aligned to the right.
                    'alignRight'
                ]
            },
        })
        .then( 
            editor => {
                My_editor = editor;
            } 
        )
        .catch( error => {
            //console.error( error );
        } );

        ClassicEditor
        .create( document.querySelector( '#editor' ), {
            ckfinder: {
                uploadUrl: '/admin/Ckfinder/image_upload'
            },
            //plugins: [ Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize ],
            image: {
                // You need to configure the image toolbar, too, so it uses the new style buttons.
                toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight' ],

                styles: [
                    // This option is equal to a situation where no style is applied.
                    'full',

                    // This represents an image aligned to the left.
                    'alignLeft',

                    // This represents an image aligned to the right.
                    'alignRight'
                ]
            },
        })
        .then( 
            editor => {
                My_editor = editor;
            } 
        )
        .catch( error => {
            //console.error( error );
        } );

        $('#item_form').on('submit', function(){
            $('#editor').val(My_editor.getData());
            console.log(My_editor.getData());
            return true;
        });

      </script>

      @yield('script')


    </body>
</html>
