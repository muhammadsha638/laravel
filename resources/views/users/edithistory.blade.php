@extends('header')
@section('title','Edit Transcribe')
@section('constant')





@if(session()->has('trascript'))
<div class="container">
     <div class="row">
       @if(session()->has('message'))<h6> {{ session()->get('message') }}</h6>@endif
    <div class="col-sm-4" style="border: 1px solid #ece4e4;box-shadow: 2px 11px 25px #ddd8d8;border-radius: 10px;">
    <div style="padding-top: 15px;text-align:center;"><h3>Transcribe form</h3></div>
   <form action="{{ route('user.transcribe.api') }}" name="edittranscribe-form" class="needs-validation" novalidate method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden"name="editfid" id="editfid" value="{{ encrypt($usersfiles->id) }}">
    <div class="form-group">
      <label for="filelanguage">Language:</label>
      <select class="form-control" id="filelanguage" name="filelanguage" required>
      <option value="">choose language</option>
      <option value="en-US" @if( session()->get('fileLanguage') == 'en-US') selected="selected" @endif>English</option>
      <option value="en-IN" @if( session()->get('fileLanguage') == 'en-IN') selected="selected" @endif>English(indian)</option>
      <option value="hi-IN" @if( session()->get('fileLanguage') == 'hi-IN') selected="selected" @endif>Hindi</option>
      <option value="ml-IN" @if( session()->get('fileLanguage') == 'ml-IN') selected="selected" @endif>Malayalam</option>
      <option value="ta-IN" @if( session()->get('fileLanguage') == 'ta-IN') selected="selected" @endif>Tamil</option>
      <option value="bn-IN" @if( session()->get('fileLanguage') == 'bn-IN') selected="selected" @endif>Bengali</option>
      <option value="mr-IN" @if( session()->get('fileLanguage') == 'mr-IN') selected="selected" @endif>Marathi</option>
      <option value="ur-IN" @if( session()->get('fileLanguage') == 'ur-IN') selected="selected" @endif>Urdu</option>
      <option value="te-IN" @if( session()->get('fileLanguage') == 'te-IN') selected="selected" @endif>Telugu</option>
      <option value="gu-IN" @if( session()->get('fileLanguage') == 'gu-IN') selected="selected" @endif>Gujarati</option>
      <option value="kn-IN" @if( session()->get('fileLanguage') == 'kn-IN') selected="selected" @endif>Kannada</option>
      <option value="pa-Guru-IN" @if( session()->get('fileLanguage') == 'pa-Guru-IN') selected="selected" @endif>Punjabi</option>
     </select>
      <!-- <div class="invalid-feedback">Please fill out this field.</div> -->
    </div>
    <div class="form-group">
      <label for="audiofile">Audio:</label>
      <input type="file" class="form-control" id="audiofile" name="audiofile" required>
      @error('audiofile')<div class="alert alert-danger">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
    <div class="" style="text-align:center;">
    <input type="submit" class="btn btn-info" value="Transcribe" name="submit" id="submit">
    </div>
    </div>
  </form>
  <div class="form-group">
    <div class="" style="height: 212px;">
    @if(session()->has('trascript'))
    <p>File Name : {{ session()->get('realfilename') }}</p>
    <p>File Type : {{ session()->get('extension') }}</p>
    <p>File size : {{ session()->get('filesize') }}</p>
    <p>File Languange : {{ session()->get('fileLanguagefullname') }}</p>
    <p>File Length : {{ session()->get('filelength') }}</p>
    <audio controls>
  <source src="{{ asset ('storage/audio/'.session()->get('audiostorename')) }}" type="audio/mp3">
  <source src="{{ asset ('storage/audio/'.session()->get('audiostorename')) }}" type="audio/wav">
  Your browser does not support the audio element.
</audio>
    @endif
    </div>
    </div>
</div>
<div class="col-sm-8" style="border: 1px solid #ece4e4;box-shadow: 2px 11px 25px #ddd8d8;border-radius: 10px;">

<div class="form-group">
<div class="" style="padding-top: 25px;">
      <textarea class="form-control" name="transcribe" id="transcribe" cols="30" rows="20" onkeyup="edittranscribe()">  @if(session()->has('trascript')){{ session()->get('trascript') }}@endif</textarea>
      @if(session()->has('trascript'))
      <div class="btn-down">
      <button class="btn btn-translate" name="translateicon" id="translateicon"><i class="bi bi-translate"></i></button>
      <button type="button" class="btn btn-clipboard" title="Copy to clipboard" onclick="copyText();" style="float:right;"><i class="bi bi-clipboard"></i></button>
      </div>
      @else
      <div class="btn-down" style="visibility: hidden;">
      <button class="btn"><i class="bi bi-translate"></i></button>
      <button type="button" class="btn" style="float:right;"><i class="bi bi-clipboard"></i></button>
      </div>
      @endif
    </div>
</div>
</div>
</div>
<div class="row" style="padding-top:5%;display:none" id="translateresultdiv">
<div class="col-sm-12" style="border: 1px solid #ece4e4;box-shadow: 2px 11px 25px #ddd8d8;border-radius: 10px;">
<div class="form-group" style="padding-top: 25px;">
      <label for="translang">Language:</label>
      <select class="form-control" id="translang" name="translang" required>
      <option value="">choose language</option>
              <option value="en">English</option>
              <option value="ml">Malayalam</option>
              <option value="ta">Tamil</option>
     </select>
     <label id="translang-error" class="error" for="translang" style="display:none">Please choose language</label>
    </div>

    <div class="form-group">
    <div class="" style="text-align:center;padding-top: 25px;">
    <button class="btn btn-info" name="translatebtn" id="translatebtn"><i class="bi bi-translate"></i>Translate</button>
    </div>
    </div>

    <div class="form-group">
<div class="" >
    <textarea class="form-control" name="translatetextarea" id="translatetextarea" cols="30" rows="10" onkeyup="myTranslateeditFunction()"></textarea>
</div>
</div>

</div>
</div>

@if(session()->has('trascript'))
<div class="row">
<div class="col-md-12" style="text-align: end;padding:0;padding-top:1%;">
     <form action="{{ route('update.transcribe') }}" method="post" style="margin-bottom:0">
     @csrf
        <input type="hidden" name="f_id" id="f_id" value="{{ encrypt($usersfiles->id) }}">
         <input type="hidden" name="f_lang" id="f_lang" value="{{ session()->get('fileLanguage') }}">
         <input type="hidden" name="f_name" id="f_name" value="{{ session()->get('audiostorename') }}">
         <input type="hidden" name="f_realname" id="f_realname" value="{{ session()->get('realfilename') }}">
         <input type="hidden" name="f_type" id="f_type" value="{{ session()->get('extension') }}">
         <input type="hidden" name="f_size" id="f_size" value="{{ session()->get('filesize') }}">
         <input type="hidden" name="f_duration" id="f_duration" value="{{ session()->get('filelength') }}">
         <input type="hidden" name="f_transcribe" id="f_transcribe" value="{{ session()->get('trascript') }}">
         <input type="hidden" name="f_translate" id="f_translate" value="">
         <input type="hidden" name="f_translate_lang" id="f_translate_lang" value="">
         <button class="btn btn-info" name="save" id="save"><i class="bi bi-check-lg"></i>Save</button>
     </form>
     </div>
</div>
@endif
</div>

@else

<div class="container">
     <div class="row">
     @if(session()->has('message'))<h6> {{ session()->get('message') }}</h6>@endif
    <div class="col-sm-4" style="border: 1px solid #ece4e4;box-shadow: 2px 11px 25px #ddd8d8;border-radius: 10px;">
    <div style="padding-top: 15px;text-align:center;"><h3>Edit Transcribe form</h3></div>
   <form action="{{ route('user.transcribe.api') }}" name="edittranscribe-form" class="needs-validation" novalidate method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden"name="editfid" id="editfid" value="{{ encrypt($usersfiles->id) }}">
    <div class="form-group">
      <label for="filelanguage">Language:</label>
      <select class="form-control" id="filelanguage" name="filelanguage" required>
      <option value="">choose language</option>
      <option value="en-US" @if($usersfiles->file_lang == 'en-US') selected="selected" @endif>English</option>
      <option value="en-IN" @if($usersfiles->file_lang == 'en-IN') selected="selected" @endif>English(indian)</option>
      <option value="hi-IN" @if($usersfiles->file_lang == 'hi-IN') selected="selected" @endif>Hindi</option>
      <option value="ml-IN" @if($usersfiles->file_lang == 'ml-IN') selected="selected" @endif>Malayalam</option>
      <option value="ta-IN" @if($usersfiles->file_lang == 'ta-IN') selected="selected" @endif>Tamil</option>
      <option value="bn-IN" @if($usersfiles->file_lang == 'bn-IN') selected="selected" @endif>Bengali</option>
      <option value="mr-IN" @if($usersfiles->file_lang == 'mr-IN') selected="selected" @endif>Marathi</option>
      <option value="ur-IN" @if($usersfiles->file_lang == 'ur-IN') selected="selected" @endif>Urdu</option>
      <option value="te-IN" @if($usersfiles->file_lang == 'te-IN') selected="selected" @endif>Telugu</option>
      <option value="gu-IN" @if($usersfiles->file_lang == 'gu-IN') selected="selected" @endif>Gujarati</option>
      <option value="kn-IN" @if($usersfiles->file_lang == 'kn-IN') selected="selected" @endif>Kannada</option>
      <option value="pa-Guru-IN" @if($usersfiles->file_lang == 'pa-Guru-IN') selected="selected" @endif>Punjabi</option>
     </select>
      <!-- <div class="invalid-feedback">Please fill out this field.</div> -->
    </div>
    <div class="form-group">
      <label for="audiofile">Audio:</label>
      <input type="file" class="form-control" id="audiofile" name="audiofile" required >
      <!-- <div class="invalid-feedback">Please fill out this field.</div> -->
    </div>
    <div class="form-group">
    <div class="" style="text-align:center;">
    <input type="submit" class="btn btn-info" value="Transcribe" name="submit" id="submit">
    </div>
    </div>
  </form>
  @php $arrylanguage = array (
    'en-US' => "English",
    'en-IN' => "English",
    'hi-IN' => "Hindi",
    'ml-IN' => "Malayalam",
    'ta-IN' => "Tamil",
    'bn-IN' => "Bengali",
    'mr-IN' => "Marathi",
    'ur-IN' => "Urdu",
    'te-IN' => "Telugu",
    'gu-IN' => "Gujarati",
    'kn-IN' => "Kannada",
    'pa-Guru-IN'=> "Punjabi"
    );
    @endphp
  <div class="form-group">
    <div class="" style="height: 212px;">
    <p>File Name : {{ $usersfiles->file_realname }}</p>
    <p>File Type : {{ $usersfiles->file_type }}</p>
    <p>File size : {{ $usersfiles->file_size }}</p>
    <p>File Languange : {{ $arrylanguage[$usersfiles->file_lang] }}</p>
    <p>File Duration : {{ $usersfiles->file_duration }}</p>
    <audio controls>
  <source src="{{ asset ('storage/audio/'.$usersfiles->file_name) }}" type="audio/mp3">
  <source src="{{ asset ('storage/audio/'.$usersfiles->file_name) }}" type="audio/wav">
  Your browser does not support the audio element.
</audio>

    </div>
    </div>
</div>
<div class="col-sm-8" style="border: 1px solid #ece4e4;box-shadow: 2px 11px 25px #ddd8d8;border-radius: 10px;">

<div class="form-group">
<div class="" style="padding-top: 25px;">
      <textarea class="form-control" name="transcribe" id="transcribe" cols="30" rows="20" onkeyup="edittranscribe()">{{ $usersfiles->file_transcribe_text }}</textarea>

      <div class="btn-down">
      <button class="btn btn-translate" name="translateicon" id="translateicon"><i class="bi bi-translate"></i></button>
      <button type="button" class="btn btn-clipboard" title="Copy to clipboard" onclick="copyText();" style="float:right;"><i class="bi bi-clipboard"></i></button>
      </div>

    </div>
</div>
</div>
</div>
<div class="row" style="padding-top:5%;display:none" id="translateresultdiv">
<div class="col-sm-12" style="border: 1px solid #ece4e4;box-shadow: 2px 11px 25px #ddd8d8;border-radius: 10px;">
<div class="form-group" style="padding-top: 25px;">
      <label for="translang">Language:</label>
      <select class="form-control" id="translang" name="translang" required>
      <option value="">choose language</option>
              <option value="en" @if($usersfiles->file_translation_lang == 'en') selected="selected" @endif>English</option>
              <option value="ml" @if($usersfiles->file_translation_lang == 'ml') selected="selected" @endif>Malayalam</option>
              <option value="ta" @if($usersfiles->file_translation_lang == 'ta') selected="selected" @endif>Tamil</option>
     </select>
     <label id="translang-error" class="error" for="translang" style="display:none">Please choose language</label>
    </div>

    <div class="form-group">
    <div class="" style="text-align:center;padding-top: 25px;">
    <button class="btn btn-info" name="translatebtn" id="translatebtn"><i class="bi bi-translate"></i>Translate</button>
    </div>
    </div>

    <div class="form-group">
<div class="" >
    <textarea class="form-control" name="translatetextarea" id="translatetextarea" cols="30" rows="10" onkeyup="myTranslateeditFunction()">{{ $usersfiles->file_translate_text }}</textarea>
</div>
</div>

</div>
</div>

<div class="row">
<div class="col-md-12" style="text-align: end;padding:0;padding-top:1%;">
     <form action="{{ route('update.transcribe') }}" method="post" style="margin-bottom:0">
     @csrf
         <input type="hidden" name="f_id" id="f_id" value="{{ encrypt($usersfiles->id) }}">
         <input type="hidden" name="f_lang" id="f_lang" value="{{ $usersfiles->file_lang }}">
         <input type="hidden" name="f_name" id="f_name" value="{{ $usersfiles->file_name }}">
         <input type="hidden" name="f_realname" id="f_realname" value="{{ $usersfiles->file_realname }}">
         <input type="hidden" name="f_type" id="f_type" value="{{ $usersfiles->file_type }}">
         <input type="hidden" name="f_size" id="f_size" value="{{ $usersfiles->file_size }}">
         <input type="hidden" name="f_duration" id="f_duration" value="{{ $usersfiles->file_duration }}">
         <input type="hidden" name="f_transcribe" id="f_transcribe" value="{{ $usersfiles->file_transcribe_text }}">
         <input type="hidden" name="f_translate" id="f_translate" value="{{ $usersfiles->file_translate_text }}">
         <input type="hidden" name="f_translate_lang" id="f_translate_lang" value="{{ $usersfiles->file_translation_lang }}">
         <a href="{{ route('user.history') }}" class="btn btn-warning" name="cancel" id="cancel"><i class="bi bi-x-circle"></i>cancel</a>
         <button class="btn btn-info" name="save" id="save"><i class="bi bi-check-lg"></i>Edit</button>
     </form>
     </div>
</div>

</div>

@endif

<script>

$(function() {
    $("form[name='edittranscribe-form']").validate({
     rules: {
        filelanguage: "required",
       audiofile: "required",

    },

    messages: {
        filelanguage: "Please choose file language",
        audiofile: "Please select your file",

    },
      submitHandler: function(form) {
      form.submit();
    }
  });

//   validate translate

});

function copyText() {

var Text = document.getElementById("transcribe");
Text.select();
navigator.clipboard.writeText(Text.value);
var tooltip = document.getElementById("myTooltip");
tooltip.innerHTML = "Copied";

}

function edittranscribe() {
   var tval = $.trim($("#transcribe").val());
    if (tval != "") {
       $('#f_transcribe').val(tval);
    }
}

$("#translateicon").click(function() {
    // assumes element with id='button'
    $("#translateresultdiv").toggle();
});

  $(document).ready(function () {
    $('#translatebtn').click(function () {
      transtext = $("#f_transcribe").val();
      translang = $("#translang").val();
      if(translang=='')
      {
        $('#translang-error').show();
        return false;
      }
      var csrfToken = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '{{ route("translate.ajax-route") }}',
            data: {

              'text':transtext,
              'lang':translang,
              '_token':csrfToken
            },
            success: function (data) {
               $("#translatetextarea").html(data);
               $("#f_translate_lang").val(translang);
               $("#f_translate").val(data);
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.log(xhr.responseText);
            }
        });
    });
});
$("#translang").change(function () {
    if($(this).val()!='');
    $('#translang-error').hide();
});
function myTranslateeditFunction() {
   var tval = $.trim($("#translatetextarea").val());
    if (tval != "") {
       $('#f_translate').val(tval);
    }
}
</script>
@endsection


