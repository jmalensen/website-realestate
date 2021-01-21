<?php

namespace App\Console\Commands;

use App\Models\Import;
use App\Models\Property;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ImportPropertiesImmofacile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:immofacile {--namefile=export}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to handle import of properties from xml of export Immofacile';

    private $import;
    private $importStartTime;
    private $importEndTime;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Mode to retrieve the file
        $mode = 'url';

        $nameFile = $this->option('namefile');

        // URL to get the file
        $url = 'http://site.com/fiche.xml';

        // Store info about import
        $this->import = new Import();
        $this->import->date_last_import = Carbon::now();
        $this->import->save();
        $this->import->refresh();

        $this->importStartTime = Carbon::now();

        // Handle import articles (import articles failed)
        try{
            $articles = $this->importProperties($url, $mode, $ftpParams);
        } catch(Exception $e){
            return $e->getMessage();
        }
    }


    /**
     * Import properties
     * @param $url
     * @param $mode
     * @param $ftpParams
     * @throws Exception
     */
    private function importProperties($url, $mode, $ftpParams){

        // Switch mode to retrieve file
        switch ($mode){
            case 'sftp':
                break;

            case 'ftp':
                if(empty($ftpParams)){
                    $message = __('No parameter for FTP !');
                    $this->logImport($message);
                    throw new Exception( $message );
                }

                $data = $this->importZipFileFromFTP($ftpParams);
                // If there is no data
                if(empty($data) ){
                    $message = __('No data found in this FTP !');
                    $this->logImport($message);
                    throw new Exception( $message );
                }
                // If there is no file in result throw Exception received in result
                if(empty($data['uploadedFile']) ){
                    $this->logImport(__('No file found') );
                    throw new Exception($data);
                }

                $filepath = $data['uploadedFile'];
                break;

            case 'url':
                $data = $this->importFileFromURL($url);
                // If there is no data
                if(empty($data)){
                    $message = __('No data found in this URL !');
                    $this->logImport($message);
                    throw new Exception( $message );
                }
                $filepath = $data['uploadedFile'];
                break;
        }

        // If zipfile
        //$path = $this->extractZipFile($filepath);

        // If xml file directly
        // Get the absolute path to $file
        $path = pathinfo(realpath($filepath), PATHINFO_DIRNAME);

        // If there is no path
        if(empty($path)){
            $message = __('No path found !');
            $this->logImport($message);
            throw new Exception( $message );
        }

        $listCurrentProperties = Property::all();

        $pathXmlFile = $this->updateProperties($path, 'export', $listCurrentProperties);
        // If there is no pathXmlFile
        if(empty($pathXmlFile)){
            $message = __('Update of data failed !');
            $this->logImport($message);
            throw new Exception( $message );
        }

        $this->deleteFiles($filepath, $pathXmlFile);
    }


    /**
     * Import zipFile from specified SFTP
     * @param $sftpParams
     */
    private function importZipFileFromSFTP($sftpParams){
        //==================================================================
       // SFTP
       $host = 'localhost';
       $port = 22;
       $username = 'username';
       $password = 'password';
       $remoteDir = ''; //the complete path
       $localDir = wp_upload_dir()['path']; //relative or absolute path
       $file = 'testxml.zip';

       // If function ssh2_connect doesn't exist
       if (!function_exists("ssh2_connect")) {
           die('Function ssh2_connect not found, you cannot use ssh2 here');
       }

       try{
           // If connexion not possible
           $connection = ssh2_connect($host, $port);
           if (!$connection) {
               throw new Exception("Could not connect to $host on port $port");
           }

           // If authentification problem
           $auth = ssh2_auth_password($connection, $username, $password);
           if (!$auth) {
               throw new Exception("Could not authenticate with username $username and password ");
           }

           // If not possible to initialize
           $sftp = ssh2_sftp($connection);
           if (!$sftp){
               throw new Exception("Could not initialize sftp.");
           }

           // Get file content
           if( ssh2_scp_recv($connection, $remoteDir.$file, $localDir.$file) ) {

               echo "File Download Success";
           } else {
               echo "File Download Failed";
               throw new Exception("File Download Failed.");
           }
       } catch(Exception $e){
           echo "Error due to :".$e->getMessage();
       }
    }


    /**
     * Import zipFile from specified FTP
     * @param $ftpParams
     * @return array|boolean
     */
    public function importZipFileFromFTP($ftpParams){

        // FTP variables
        $ftpServer = $ftpParams['host'];
        $ftpUserName = $ftpParams['username'];
        $ftpUserPassword = $ftpParams['password'];

        // Get path to put files
        $filenameWithoutExt = $this->get_local_dir();

        // Files variables
        $filenameServer = $ftpParams['filename']; // 'export'
        $localFile = $filenameWithoutExt.'/'.$filenameServer.'.zip';
        $serverFile = '/www/'.$filenameServer.'.zip';

        try {
            // Basic connexion
            $connexionId = ftp_connect($ftpServer, 21);
            if(!$connexionId){
                $message = "Could not connect to host : < $ftpServer >";
                $this->logImport($message);
                throw new Exception($message);
            }

            // Login to ftp (need username and password)
            $loginResult = ftp_login($connexionId, $ftpUserName, $ftpUserPassword);
            if (!$loginResult) {
                $message = "Login with username : < $ftpUserName > failed";
                $this->logImport($message);
                throw new Exception($message);
            }

            // To enable passive mode
            $passiveMode = ftp_pasv($connexionId, true);
            if(!$passiveMode){
                $message = "Failed to enable passive mode";
                $this->logImport($message);
                throw new Exception($message);
            }

            // List file in folder
            //var_dump( ftp_nlist ( $connexionId , '/www' ) );

            // Attempt to download server file to local file
            $download = ftp_get($connexionId, $localFile, $serverFile, FTP_BINARY);
            if (!$download) {
                $message = "Failed to download file : < $serverFile >";
                $this->logImport($message);
                throw new Exception($message);
            }

            // Close connexion
            ftp_close($connexionId);

        } catch(Exception $e){
            $this->logImport($e->getMessage());
            return $e->getMessage();
        }

        $result = [
            'uploadedFile' => $localFile,
            'filename' => $filenameWithoutExt,
        ];

        return $result;
    }


    /**
     * Import File from specified URL
     * @param $url
     * @return string
     */
    private function importFileFromURL($url){

        try{
            // From HTTP(S)
            $pathParts = pathinfo($url);

            // Handle retrieving of zip file and put it in wordpress upload directory
            $filename = $pathParts['filename'];
            $filenameWithExt = $filename. '.' .$pathParts['extension'];

            // Get path to put xml file
            $filenameWithoutExt = $this->get_local_dir();

            $targetPath = $filenameWithoutExt.'/'.$filenameWithExt;

            try{
                $result = copy($url, $targetPath);

                $result = [
                    'uploadedFile' => $targetPath,
                    'filename' => $filename,
                ];

                return $result;

            } catch(Exception $e){
                $this->logImport( __('Failed to copy xml from URL ').$url );
                return $e->getMessage();
            }

        } catch(Exception $e){
            $this->logImport( __('Failed to read export xml in url given') );
            return $e->getMessage();
        }
    }


    /**
     * Extract xml file from zipFile
     * @param $filenameUploaded
     * @return mixed
     */
    public function extractZipFile($filenameUploaded){

        // Get the absolute path to $file
        $path = pathinfo(realpath($filenameUploaded), PATHINFO_DIRNAME);

        $zipFile = new ZipArchive();
        $res = $zipFile->open($filenameUploaded);

        // If extraction is ok
        if ($res === TRUE) {

            // Extract it to the path we determined above
            $zipFile->extractTo($path);
            $zipFile->close();

        } else {
            $this->logImport("Couldn't open '$filenameUploaded'");
            return false;
        }

        return $path;
    }


    /**
     * Update properties
     * @param $path
     * @param $filename
     * @param Collection $listCurrentProperties
     * @return bool|string
     * @throws Exception
     */
    public function updateProperties($path, $filename, Collection $listCurrentProperties){

        // Read xml file
        $pathXmlFile = $path .'/'. $filename .'.xml';

        try{
            $xmldata = simplexml_load_file($pathXmlFile);
           // If there is an error
           if(!$xmldata){
               $this->logImport(__("Failed to open xml File"));
               return false;
           }
        } catch(Exception $e){
            $this->logImport(__("Failed to open xml File") );
            return $e->getMessage();
        }

        $listNewIdImmo = [];
        foreach($xmldata->children() as $bien){
            try{
                // Prepare vars
                $data['code_immo']                 = (string) trim( $bien->INFO_GENERALES->AFF_ID );
                $data['titleFR']                   = (string) trim( $bien->INTITULE->FR );
                $data['titleEN']                   = (string) trim( $bien->INTITULE->US );
                $data['titleDE']                   = (string) trim( $bien->INTITULE->DE );
                $data['descriptionFR']             = (string) trim( $bien->COMMENTAIRES->FR );
                $data['descriptionEN']             = (string) trim( $bien->COMMENTAIRES->US );
                $data['descriptionDE']             = (string) trim( $bien->COMMENTAIRES->DE );
                $dateOffer                         = (string) trim( $bien->INFO_GENERALES->DATE_CREATION );
                // Europe/Paris timezone
                $data['date_offer']                = Carbon::createFromFormat('Y-m-d H:i:s', $dateOffer, 'Europe/Paris');

                $dateUpdated                       = (string) trim( $bien->INFO_GENERALES->DATE_MAJ );
                $dateUpdated                       = $dateUpdated.' 00:00:00';
                // Europe/Paris timezone
                $data['date_updated_immo']         = Carbon::createFromFormat('d/m/Y H:i:s', $dateUpdated, 'Europe/Paris');

                $data['price']                     = (int) trim( $bien->VENTE->PRIX );
                $data['zipcode']                   = (string) trim( $bien->LOCALISATION->CODE_POSTAL );
                $data['city']                      = (string) trim( $bien->LOCALISATION->VILLE );
                $data['country']                   = (string) trim( $bien->LOCALISATION->PAYS );

                // If it's a house
                $typeXMLProperty = '';
                if( !empty($bien->MAISON) ){
                    $typeXMLProperty = 'MAISON';
                    $data['type_property'] = Property::TYPE_HOUSE;
                } else if( !empty($bien->APPARTEMENT) ){
                    $typeXMLProperty = 'APPARTEMENT';
                    $data['type_property'] = Property::TYPE_FLAT;
                } else if( !empty($bien->PROGRAMME_NEUF) ){
                    $typeXMLProperty = 'PROGRAMME_NEUF';
                    $data['type_property'] = Property::TYPE_NEW;
                } else if( !empty($bien->DEMEURE) ){
                    $typeXMLProperty = 'DEMEURE';
                    $data['type_property'] = Property::TYPE_BUILDING;
                } else{
                    $typeXMLProperty = 'NONCONNU';
                    $data['type_property'] = null;
                }

                $data['living_area']             = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->SURFACE_HABITABLE ) );
                $data['sejour_area']             = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->SURFACE_SEJOUR ) );
                $data['field_area']              = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->SURFACE_TERRAIN ) );
                $data['carrez_area']             = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->SURFACE_CARREZ ) );
                $data['nb_room']                 = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->NBRE_PIECES ) );
                $data['bedroom']                 = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->NBRE_CHAMBRES ) );
                $data['floor']                   = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->NBRE_NIVEAUX ) );
                $data['type']                    = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->CATEGORIE ) );
                $data['favorite']                = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->COUP_COEUR ) );
                $data['construction_year']       = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->ANNEE_CONSTRUCTION ) );
                $data['nb_bathroom']             = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->NBRE_SALLE_BAIN ) );
                $data['nb_wc']                   = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->NBRE_WC ) );
                $data['has_furniture']           = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->MEUBLE ) );
                $data['kitchen']                 = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->CUISINE ) );

                $data['mitoyen']                 = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->MITOYENNETE ) );
                $data['general_state']           = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->ETAT_GENERAL ) );
                $data['nb_waterroom']            = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->NBRE_SALLE_EAU ) );
                $data['nb_cave']                 = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->NBRE_CAVES ) );
                $data['standing']                = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->STANDING ) );
                $data['type_parking']            = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->TYPE_STATIONNEMENT ) );
                $data['interphone']              = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->INTERPHONE ) );
                $data['garden']                  = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->JARDIN ) );
                $data['exterior_state']          = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->ETAT_EXTERIEUR ) );
                $data['bedroom_rdc']             = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->CHAMBRE_RDC ) );
                $data['one_level']               = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->PLAIN_PIED ) );
                $data['double_sejour']           = (int) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->SEJOUR_DOUBLE ) );
                $data['sanitation']              = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->ASSAINISSEMENT ) );
                $data['alarm']                   = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->ALARME ) );
                $data['new_old']                 = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->NEUF_ANCIEN ) );
                $data['expo_sejour']             = (string) trim( $this->valueOrNull( $bien->{$typeXMLProperty}->EXPOSITION_SEJOUR ) );

            } catch(Exception $e){
                $this->logImport( __('Failed to read xml in file') );
                return $e->getMessage();
            }

            // Get property with code_immo
            $property = $listCurrentProperties->firstWhere('code_immo', $data['code_immo']);

            // If property exist, update
            $idProperty = '';
            if(!empty($property) ){

                // To compare the dates
                $modifiedDateProperty = $property->date_updated_immo;
                $timeStampModified = strtotime( $modifiedDateProperty );

                try{
                    $dateXML = (string) $bien->INFO_GENERALES->DATE_MAJ;
                    $timeStampXML = strtotime(str_replace('/', '-', $dateXML));
                } catch(Exception $e){
                    $this->logImport( __('Failed to read DATE_MODIF xml in file') );
                    return $e->getMessage();
                }

                // If timestampXML newer than last modified date of laravel property stored
                // ->update
                if($timeStampXML > $timeStampModified){

                    // Update property
                    $property->fill($data);

                    // If there are images
                    $localDir = null;
                    $this->handleImages($property, $bien, $path, $data['code_immo']);

                    $property->update();
                } else{
                }

                $idProperty = $property->id;

            } else{

                $property = new Property();
                $property->fill($data);

                // If there are images
                $this->handleImages($property, $bien, $path, $data['code_immo']);

                $property->save();

                $idProperty = $property->id;
            }

            $listNewIdImmo[$idProperty] = $data['code_immo'];
        }

        $listCurrentPropertiesArray = $listCurrentProperties->pluck('code_immo', 'id')->toArray();

        // Compare 2 arrays
        $listPropertiesRemoved = array_diff($listCurrentPropertiesArray, $listNewIdImmo);
        if( !empty($listPropertiesRemoved) ){

            // Get keys of array
            $idPropertiesRemoved = array_keys($listPropertiesRemoved);
            foreach ($idPropertiesRemoved as $idPropertyRemoved){
                //Remove property (soft delete)
                $propertyToDelete = Property::find($idPropertyRemoved);
                $propertyToDelete->delete();
            }
        }

        return $pathXmlFile;
    }


    /**
     * Check if node value is empty
     * @param $value
     * @return |null
     */
    private function valueOrNull($value){
        return ( (bool) ($value) ) ? $value : null;
    }


    /**
     * Handle images of property
     * @param Property $property
     * @param $bien
     * @param $path
     * @param $periclesId
     * @return string
     */
    private function handleImages(Property $property, $bien, $path, $immoId){
        try{
            $storagePath = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();
            $localDir = $storagePath.'imagesProperties/'.$immoId.'/'; // => ".../imagesProperties/<1>/"

            // Create specific folder for backup
            if(!file_exists($localDir)){
                File::makeDirectory($localDir, 0711, true, true);
            } else{
                // Delete old files if property changed and put new ones
                File::deleteDirectory($localDir);
                File::makeDirectory($localDir, 0711, true, true);
            }

            // Test if there are images
            if(isset($bien->IMAGES) && !empty($bien->IMAGES) ){
                $i = 0;
                $tab = [];
                foreach($bien->IMAGES->children() as $image){

                    $strPathImage = (string) trim($image);
                    $imageName = uniqid (rand(), true).'.jpg';
                    $pathFileTarget = $localDir.$imageName;

                    try{
                        $result = copy($strPathImage, $pathFileTarget);

                        $tab[$i] = 'storage/imagesProperties/'.$immoId.'/'.$imageName;

                    } catch(Exception $e){
                        $this->logImport( __('Failed to copy IMAGE from URL ').$strPathImage );
                        return $e->getMessage();
                    }

                    $i++;
                }
                $property->thumbnail_path = (!empty($tab[0]) ) ? $tab[0] : 'images/no-house-thumbnail.jpg';

                // If there is images
                if($i > 0){
                    $property->images_path = 'imagesProperties/'.$immoId.'/';
                }
            }

        } catch(Exception $e){
            $this->logImport( __('Failed to read IMAGES xml in file') );
            return $e->getMessage();
        }
    }


    /**
     * Delete useless files
     * @param $filenameUploaded
     * @param $pathXmlFile
     */
    private function deleteFiles($filenameUploaded, $pathXmlFile){

        // Delete files on server
        unlink($filenameUploaded);
        unlink($pathXmlFile);

	   // If there is a image folder to delete afterward
	   // Get path to storage folder
	   $storagePath = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();

	   // Time for archive
	   date_default_timezone_set('Europe/Paris');
	   $timeArchive = date('Y-m-d');

	   $localDir = $storagePath.'importer/'.$timeArchive;

	   $deletedSucceed = File::deleteDirectory($localDir.'/images');
	   if(!$deletedSucceed){
		   $this->logImport(__('Failed to delete useless files'));
	   }

        $this->logImport( __('Import succeed') );
    }


    /**
     * Get directory to put files from import
     * @return string
     */
    public function get_local_dir(){

        // Get path to storage folder
        $storagePath = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();

        // Time for archive
        date_default_timezone_set('Europe/Paris');
        $timeArchive = date('Y-m-d');

        $localDir = $storagePath.'importer/'.$timeArchive;

        // Create specific folder for backup
        if(!file_exists($localDir)){
            File::makeDirectory($localDir, 0711, true, true);
        }

        return $localDir;
    }


    /**
     * Log Import
     * @param $message
     */
    private function logImport($message){
        $this->importEndTime = Carbon::now();

        $this->import->duration = $this->importStartTime->diffInSeconds($this->importEndTime);
        $this->import->status = $message;
        $this->import->update();
    }
}
