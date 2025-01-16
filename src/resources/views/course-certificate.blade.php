<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    {{-- <div class="certificate">
        <h1>Certificate of Completion</h1>
        <p>This certifies that</p>
        <h2>{{ $user->name }}</h2>
        <p>has successfully completed the course</p>
        <h3>{{ $course->name }}</h3>
        <p>on {{ now()->format('F d, Y') }}</p>
    </div> --}}
        <div class="container pm-certificate-container">
          <div class="outer-border"></div>
          <div class="inner-border"></div>
          
          <div class="pm-certificate-border col-xs-12">
            <div class="row pm-certificate-header">
              <div class="pm-certificate-title sans col-xs-12 text-center">
                <h2>Certificate Course Completion</h2>
              </div>
            </div>
      
            <div class="row pm-certificate-body">
              
              <div class="pm-certificate-block">
                  <div class="col-xs-12">
                    <div class="row">
                      <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                      <div class="pm-certificate-name margin-0 col-xs-8 text-center">
                        <span class="pm-name-text bold">{{ $course->name }}</span>
                      </div>
                      <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                    </div>
                  </div>          
      
                  <div class="col-xs-12">
                    <div class="row">
                      <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                      <div class="pm-earned col-xs-8 text-center">
                        <span class="pm-earned-text padding-0 block sans">has earned</span>
                        <span class="pm-credits-text block bold sans">on  {{ now()->format('F d, Y') }}</span>
                      </div>
                      <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                      <div class="col-xs-12"></div>
                    </div>
                  </div>
                  
                  <div class="col-xs-12">
                    <div class="row">
                      <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                      <div class="pm-course-title col-xs-8 text-center">
                        <span class="pm-credits-text block rochester" style="font-size: 40px; border-bottom: 1px solid #777">{{ $user->name ?? 'Michael' }}</span>
                      </div>
                      <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                    </div>
                  </div>
      
              </div>       
              
              <div class="col-xs-12">
                <div class="row">
                  <div class="pm-certificate-footer">
                      <div class="col-xs-4 pm-certified col-xs-4 text-center">
                        <span class="pm-credits-text block sans">Hadi Toward Santosa</span>
                        <span class="pm-empty-space block underline"></span>
                        <span class="bold block">CTO Learning Management System</span>
                      </div>
                      <div class="col-xs-4">
                        <!-- LEAVE EMPTY -->
                      </div>
                  </div>
                </div>
              </div>
      
            </div>
      
          </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
