<?php

namespace App\Http\Controllers;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Http\Request;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\StreamingRecognitionConfig;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Translate\V2\TranslateClient;
use Google\ApiCore\ApiException;
use FFMpeg\FFMpeg;
use getID3;
use App\Models\Transcribe;
use Illuminate\Validation\Rule;

class GoogleController extends Controller
{
    //view transcribtion form
    public function user_transcribtion()
    {
        return view('users.transcribe');

    }

    public function user_transcribe_action()
    {
        $Language= request('filelanguage');
        if(request()->hasFile('audiofile'))
        {
            request()->validate([
                'filelanguage' => 'required',
                'audiofile' => 'required|mimes:mp3,wav|max:2048', // Example rules
            ]);

            $extension = request('audiofile')->extension();
            $filename ='user_audio_files'.time().'.'.$extension;
            request('audiofile')->storeAs('audio',$filename);
            // $input['image'] = $filename;


        }

        $speech = new SpeechClient();
        $storage = new StorageClient();
        try {
            $storage = new StorageClient();
            $bucketName = 'my_php_storage_2';

            $path = public_path('storage/audio/'.$filename);
            // $audiofile=request('audiofile');
            // $fileName = 'laravel_malayalam.mp3';
            $bucket = $storage->bucket($bucketName);
            $object = $bucket->upload(
               fopen($path, 'r'),
               [
                   'predefinedAcl' => 'publicRead'
               ]
           );

        //    echo "File is uploaded successfully. File path is: https://storage.googleapis.com/$bucketName/$filename";

        } catch(Exception $e) {
           echo $e->getMessage();
       }
        $audioLength=$this->GetuploadAudio_Length(request('audiofile'));

        if($extension=='mp3')
        {
        $config = (new RecognitionConfig())
       ->setEncoding(AudioEncoding::ENCODING_UNSPECIFIED)
       ->setSampleRateHertz(16000) // Adjust this to match your audio's sample rate
       ->setLanguageCode(request('filelanguage'));
    }else
    {
        $config = (new RecognitionConfig())
        ->setLanguageCode(request('filelanguage'));
    }
       $audio = (new RecognitionAudio())
      ->setUri("gs://my_php_storage_2/$filename");

      $decimals=2;
      $fileSizeBytes = request('audiofile')->getSize();
      $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
      $factor = floor((strlen($fileSizeBytes) - 1) / 3);
      $final_file_Size= sprintf("%.{$decimals}f", $fileSizeBytes / (1024 ** $factor)) . ' ' . @$size[$factor];

       try {

        $operation = $speech->longRunningRecognize($config, $audio);
        $operation->pollUntilComplete();
        $response = $operation->getResult();

        // Loop through the results to get the complete transcript
        $transcript = '';

       foreach ($operation->getResult()->getResults() as $result) {
        if ($result->getAlternatives()) {
            $partialTranscript = $result->getAlternatives()[0]->getTranscript();
            $transcript .= $partialTranscript . ' '; // Add a space after each partial transcript
            }
    }

       // echo $transcript;
    } catch (ApiException $e) {
        // echo $e->getMessage();
        // $error='api';

        if($extension=='mp3')
    {

    $config = (new RecognitionConfig())
       ->setEncoding(AudioEncoding::WEBM_OPUS) // Adjust this based on your audio format
       //->setEncoding(AudioEncoding::ENCODING_UNSPECIFIED)
        ->setSampleRateHertz(16000) // Adjust this to match your audio's sample rate
       ->setLanguageCode(request('filelanguage'));
    }
    else
    {
      $config = (new RecognitionConfig())
        ->setEncoding(AudioEncoding::LINEAR16) // Adjust this based on your audio format
        ->setSampleRateHertz($this->GetMP3BitRateSampleRate($path)) // Adjust this to match your audio's sample rate
        ->setLanguageCode(request('filelanguage'))
        ->setaudioChannelCount(2)
        ->setenableSeparateRecognitionPerChannel(true);
    }

        try {

        $operation = $speech->longRunningRecognize($config, $audio);
        $operation->pollUntilComplete();
        $response = $operation->getResult();

        // Loop through the results to get the complete transcript
        $transcript = '';

       foreach ($operation->getResult()->getResults() as $result) {
        if ($result->getAlternatives()) {
            $partialTranscript = $result->getAlternatives()[0]->getTranscript();
            $transcript .= $partialTranscript . ' '; // Add a space after each partial transcript
            }
    }
    $error='';
       // echo $transcript;
    } catch (ApiException $e) {
        echo $e->getMessage();
        $error='api';
    }
    }
    $arrylanguage = array (
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
    if(request('editfid'))
    {
    return redirect()->route('user.edit.history',request('editfid'))->with('trascript',$transcript)
    ->with('extension',$extension)
    ->with('fileLanguage',$Language)
    ->with('fileLanguagefullname',$arrylanguage[$Language])
    ->with('realfilename',request('audiofile')->getClientOriginalName())
    ->with('filesize',$final_file_Size)
    ->with('audiostorename',$filename)
    ->with('filelength',$audioLength);
    }
    else
    {
    return redirect()->route('user.transcribtion.form')->with('trascript',$transcript)
    ->with('extension',$extension)
    ->with('fileLanguage',$Language)
    ->with('fileLanguagefullname',$arrylanguage[$Language])
    ->with('realfilename',request('audiofile')->getClientOriginalName())
    ->with('filesize',$final_file_Size)
    ->with('audiostorename',$filename)
    ->with('filelength',$audioLength);
    }

}



    public function translate_ajaxMethod()
    {

        $translate = new TranslateClient();

          $text = request('text');
          $lang =  request('lang');;
          $result = $translate->translate($text, [
            'target' => $lang]);
        echo $result['text'];
        die;

    }

    public function all_drivefiles()
    {

        $storage = new StorageClient();
        // Specify the bucket name
        $bucketName = 'my_php_storage_2';
        // Get a reference to the bucket
        $bucket = $storage->bucket($bucketName);
          // List objects in the bucket
        $objects = $bucket->objects();
           // Do something with the objects (e.g., list or upload files)
        foreach ($objects as $object) {
          // echo $object->name() . PHP_EOL;
        }
    }

    public function duration()
    {
        $filePath="file:///D:/xampp/htdocs/example-app/public/storage/audio/user_audio_files1696616107.mp3";

        try {
            $ffmpeg = FFMpeg::create();

            $audio = $ffmpeg->open($filePath);
            return $audio;
            // Get the audio file duration in seconds
            $duration = $audio->getFormat()->getDuration();

            // Convert duration to a human-readable format (e.g., HH:MM:SS)
            $formattedDuration = gmdate("H:i:s", $duration);

            return $formattedDuration;
        } catch (\Exception $e) {
            // Handle exceptions if the file is not found or FFmpeg fails
            return 'Error: ' . $e->getMessage();
        }
    }

    public function GetMP3BitRateSampleRate($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }

        $bitRates = array(
                          array(0,0,0,0,0),
                          array(32,32,32,32,8),
                          array(64,48,40,48,16),
                          array(96,56,48,56,24),
                          array(128,64,56,64,32),
                          array(160,80,64,80,40),
                          array(192,96,80,96,48),
                          array(224,112,96,112,56),
                          array(256,128,112,128,64),
                          array(288,160,128,144,80),
                          array(320,192,160,160,96),
                          array(352,224,192,176,112),
                          array(384,256,224,192,128),
                          array(416,320,256,224,144),
                          array(448,384,320,256,160),
                          array(-1,-1,-1,-1,-1),
                        );
        $sampleRates = array(
                             array(11025,12000,8000), //mpeg 2.5
                             array(0,0,0),
                             array(22050,24000,16000), //mpeg 2
                             array(44100,48000,32000), //mpeg 1
                            );
        $bToRead = 1024 * 12;

        $fileData = array('bitRate' => 0, 'sampleRate' => 0);
        $fp = fopen($filename, 'r');
        if (!$fp) {
            return false;
        }
        //seek to 8kb before the end of the file
        fseek($fp, -1 * $bToRead, SEEK_END);
        $data = fread($fp, $bToRead);

        $bytes = unpack('C*', $data);
        $frames = array();
        $lastFrameVerify = null;

        for ($o = 1; $o < count($bytes) - 4; $o++) {

            //http://mpgedit.org/mpgedit/mpeg_format/MP3Format.html
            //header is AAAAAAAA AAABBCCD EEEEFFGH IIJJKLMM
            if (($bytes[$o] & 255) == 255 && ($bytes[$o+1] & 224) == 224) {
                $frame = array();
                $frame['version'] = ($bytes[$o+1] & 24) >> 3; //get BB (0 -> 3)
                $frame['layer'] = abs((($bytes[$o+1] & 6) >> 1) - 4); //get CC (1 -> 3), then invert
                $srIndex = ($bytes[$o+2] & 12) >> 2; //get FF (0 -> 3)
                $brRow = ($bytes[$o+2] & 240) >> 4; //get EEEE (0 -> 15)
                $frame['padding'] = ($bytes[$o+2] & 2) >> 1; //get G
                if ($frame['version'] != 1 && $frame['layer'] > 0 && $srIndex < 3 && $brRow != 15 && $brRow != 0 &&
                    (!$lastFrameVerify || $lastFrameVerify === $bytes[$o+1])) {
                    //valid frame header

                    //calculate how much to skip to get to the next header
                    $frame['sampleRate'] = $sampleRates[$frame['version']][$srIndex];
                    if ($frame['version'] & 1 == 1) {
                        $frame['bitRate'] = $bitRates[$brRow][$frame['layer']-1]; //v1 and l1,l2,l3
                    } else {
                        $frame['bitRate'] = $bitRates[$brRow][($frame['layer'] & 2 >> 1)+3]; //v2 and l1 or l2/l3 (3 is the offset in the arrays)
                    }

                    if ($frame['layer'] == 1) {
                        $frame['frameLength'] = (12 * $frame['bitRate'] * 1000 / $frame['sampleRate'] + $frame['padding']) * 4;
                    } else {
                        $frame['frameLength'] = 144 * $frame['bitRate'] * 1000 / $frame['sampleRate'] + $frame['padding'];
                    }

                    $frames[] = $frame;
                    $lastFrameVerify = $bytes[$o+1];
                    $o += floor($frame['frameLength'] - 1);
                } else {
                    $frames = array();
                    $lastFrameVerify = null;
                }
            }
            if (count($frames) < 3) { //verify at least 3 frames to make sure its an mp3
                continue;
            }

            $header = array_pop($frames);
            $fileData['sampleRate'] = $header['sampleRate'];
            $fileData['bitRate'] = $header['bitRate'];

            break;
        }
        return $header['sampleRate'];

        //return $fileData;
    }
    public function GetuploadAudio_Length($file)
    {
        // Validate the uploaded file
        // $request->validate([
        //     'audio_file' => 'required|mimes:mp3,ogg,wav',
        // ]);

        // Get the uploaded file
        //$file =

        // Initialize the getID3 library
        $getID3 = new getID3();

        // Analyze the uploaded audio file
        $fileInfo = $getID3->analyze($file->getRealPath());

        // Get the duration of the audio in seconds
        $durationInSeconds = $fileInfo['playtime_seconds'];

        // Format the duration as a human-readable string (optional)
        $formattedDuration = gmdate('H:i:s', $durationInSeconds);

        // You can now use $durationInSeconds or $formattedDuration as needed.

        // Return a response or save the duration in your database
        // echo $durationInSeconds;
        return $formattedDuration;
       // return view('audio.uploaded', compact('durationInSeconds', 'formattedDuration'));
    }


    public function save_usertranscibe()
    {

        $input=['user_id'=>auth()->user()->id,'file_name'=>request('f_name'),'file_realname'=>request('f_realname'),'file_type' =>request('f_type'),'file_lang' =>request('f_lang'),'file_duration' =>request('f_duration'),'file_size' =>request('f_size'),'file_transcribe_text' =>request('f_transcribe'),'file_translate_text' =>request('f_translate'),'file_translation_lang' =>request('f_translate_lang')];
        $transcribe= Transcribe::Create($input);
        return redirect()->route('user.transcribtion.form')->with('message','Saved successfully');

    }
    public function users_filehistory()
    {

        // $usersfiles = Transcribe::all();
         $usersfiles = Transcribe::where('user_id',auth()->user()->id)->get();
        // $usersfiles = Transcribe::find();
        $arrylanguage = array (
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
        return view('users.history',compact('usersfiles','arrylanguage'));
    }
    public function users_editfilehistory($transcribefileid)
    {
        $usersfiles = Transcribe::find(decrypt($transcribefileid));
        return view('users.edithistory',compact('usersfiles'));
    }
    public function users_updatefilehistory()
    {

        // return request()->only('_token');
        // return request()->except('_token');
        $transcribefileid=decrypt(request('f_id'));
        $usersfiles = Transcribe::find($transcribefileid);
        $input=['file_name'=>request('f_name'),'file_realname'=>request('f_realname'),'file_type' =>request('f_type'),'file_lang' =>request('f_lang'),'file_duration' =>request('f_duration'),'file_size' =>request('f_size'),'file_transcribe_text' =>request('f_transcribe'),'file_translate_text' =>request('f_translate'),'file_translation_lang' =>request('f_translate_lang')];
        $usersfiles->update($input);
        return redirect()->route('user.history')->with('message','file updated successfully');

    }

      public function users_deletefilehistory($fid)
    {

        $usersfiles = Transcribe::find(decrypt($fid));
        $speech = new SpeechClient();
         try {
       $storage = new StorageClient();
       $bucketName = 'my_php_storage_2';
       $fileName = basename($usersfiles->file_name);
       $bucket = $storage->bucket($bucketName);
       $object = $bucket->object($fileName);
       if (file_exists(public_path('storage/audio/'.$usersfiles->file_name)))
       {
       unlink(public_path('storage/audio/'.$usersfiles->file_name));
       $object->delete();
       $usersfiles->delete();
       return redirect()->route('user.history')->with('message','file Deleted successfully');
       }
       else{
        $usersfiles->delete();
        return redirect()->route('user.history')->with('message','cant Deleted,Try again');
       }
} catch(Exception $e) {
    //echo $e->getMessage();
    $usersfiles->delete();
    return redirect()->route('user.history')->with('message','cant Deleted,Try again');
}
   }

 public function text()
    {

        $translate = new TranslateClient();

          $text = 'hello';
          $lang =  'ml';
          $result = $translate->translate($text, [
            'target' => $lang]);
        echo $result['text'];
        die;

    }
}
