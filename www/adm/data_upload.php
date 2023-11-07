<?php
//ini_set('display_errors','1');
//error_reporting(E_ALL);
include_once("inc/header.inc.php");
include_once("fx.inc.php");
//echo "kkxkxkkxkx";
if(!empty($_FILES)){
	$strResp = "";
	sleep(1);
	if($_REQUEST["inpname"]!="")
	{
		$inpname = $_REQUEST["inpname"];
		$tempFile = $_FILES[$inpname]["tmp_name"];
		if(is_uploaded_file($tempFile))
		{
			$bolDoUpl = true;
			switch ($_REQUEST["tipo"])
			{
				case "img":
					$arrOrImgAttr = getimagesize($tempFile);
					$imgType = image_type_to_mime_type($arrOrImgAttr[2]);
					if($imgType=="image/png")
						$flname = time().".png";
					else if($imgType=="image/gif")
						$flname = time().".gif";
					else
						$flname = time().".jpg";
					$targetPath = MEDIA."/images/";
					$db = "".PREFIX."_media";
					$targetFile =  str_replace('//','/',$targetPath) . $flname;
					break;
				case "data":
					$flname = cleanUp($_FILES[$inpname]["name"]);
					if(file_exists(DATA."/".$flname))
					{
						$tfl = count(glob(DATA."*".$flname));
						$flname = ($tfl+1)."_".$flname;
					}
					$targetPath = DATA;
					$db = "".PREFIX."_media";
					$targetFile =  str_replace('//','/',$targetPath) . $flname;
					break;
				case "vid":
					$flInfo = pathinfo(cleanUp($_FILES[$inpname]["name"]));
					$ext = $flInfo["extension"];
					$flname = $flInfo["filename"];
					$targetPath = MEDIA."/videos/$flname";
					if(!is_dir($targetPath))
						mkdir($targetPath);
					$db = "".PREFIX."_media";
					$targetFile =  str_replace('//','/',$targetPath)."/$flname.$ext";
					break;
				default:
					$strResp = 2;
					$bolDoUpl = false;
					break;
			}			
			
            
            
			if(move_uploaded_file($tempFile,$targetFile))
			{
				include_once("mysql.class.inc.php");
				if($_REQUEST["tipo"]=="img")
				{
					if(MEDIAPARAM)
						getMEDIAPARAM();
					else
						include_once("mediaparam.inc.php");
					$imgSize = getimagesize($targetFile);
					if($imgSize[0]>media_IMG_MAX_W || $imgSize[1]>media_IMG_MAX_H)
					{
						if(media_RESIZEIMG)
						{
							$bolDoUpl = resizeIMG($targetFile);
						}
						else
						{
							$errUPLMSG = str_replace("media_IMG_MAX_H",media_IMG_MAX_H,str_replace("media_IMG_MAX_W",media_IMG_MAX_W,$arrTextes["errors"]["imagesize"]));
							$strResp =  $errUPLMSG;
							$bolDoUpl = false;
						}						
					}
					if($bolDoUpl)
					{
						$bolDoUpl = createFormats($targetFile,$flname,SIZE_SMALLTHUMB_W,SIZE_SMALLTHUMB_H,true);
						$bolDoUpl = createFormats($targetFile,$flname,SIZE_THUMB_W,SIZE_THUMB_H,false);
						$bolDoUpl = createFormats($targetFile,$flname,SIZE_LARGE_W,SIZE_LARGE_H,false);
						$bolDoUpl = createFormats($targetFile,$flname,SIZE_HUGE_W,SIZE_HUGE_H,false);
					}
				}
				
				if($bolDoUpl)
				{
					// if linked image, set position
					if($_REQUEST["isRef"])
					{
						switch($_REQUEST["refType"])
						{
							case "gall":
								$dbj = "".PREFIX."_gallery_media";
								$dbj_ID = "id_GALL";
								break;
							case "news":
								$dbj = "".PREFIX."_news_media";
								$dbj_ID = "id_NWS";
								break;
							case "pages":
								$dbj = "".PREFIX."_pages_media";
								$dbj_ID = "id_PAG";
								break;
						}
						$strSQLPOS = "(SELECT IF(MAX(db.pos) IS NULL,1,MAX(db.pos)+1) AS ps FROM $db AS db JOIN $dbj AS dbj ON db.id_MED=dbj.id_MED WHERE $dbj_ID=".$_REQUEST["id"].")";
					}
					else
						$strSQLPOS = 0;
					
					if($_REQUEST["tipo"]=="vid")
					{
						$strSQL = "INSERT INTO $db (tipo,url,pos) 
									SELECT 'vid','$flname',$strSQLPOS 
									FROM dual 
									WHERE NOT EXISTS (SELECT * FROM $db WHERE tipo='vid' AND url='$flname')";
					}
					else
						$strSQL = "INSERT INTO $db (tipo,url,pos) VALUES('".$_REQUEST["tipo"]."','$flname',$strSQLPOS)";
					
					try
					{
						if(!isset($objConn)) $objConn = MySQL::getIstance();
						if($objConn->i_query($strSQL))
						{
							if($_REQUEST["isRef"])
							{
								settype($_REQUEST["id"],"INT");
								$imgID = $objConn->getInsertID();
								if($imgID!=0)
									switch($_REQUEST["refType"])
									{
										case "gall":
											$strSQL = "INSERT INTO ".PREFIX."_gallery_media (id_GALL,id_MED) VALUES (".$_REQUEST["id"].",$imgID) ON DUPLICATE KEY UPDATE id_MED=$imgID";
											break;
										case "news":
											$strSQL = "INSERT INTO ".PREFIX."_news_media (id_NWS,id_MED) VALUES (".$_REQUEST["id"].",$imgID) ON DUPLICATE KEY UPDATE id_MED=$imgID";
											break;
										case "pages":
											$strSQL = "INSERT INTO ".PREFIX."_pages_media (id_PAG,id_MED) VALUES (".$_REQUEST["id"].",$imgID) ON DUPLICATE KEY UPDATE id_MED=$imgID";
											break;
									}
								if($objConn->i_query($strSQL))
								{
									if($_REQUEST["tipo"]=="data") // associate with open download page // ID=>173
									{
										if($_REQUEST["refType"]=="pages") // check if page not in download already
										{
											$strSQL = "SELECT parent_id FROM ".PREFIX."_pages WHERE id_PAG=".$_REQUEST["id"];
											try
											{
												if($rsD = $objConn->rs_query($strSQL))
												{
													$rowD = $rsD->fetchAssocArray();
													if($rowD["parent_id"]!=DOWN_idPAG)
													{
														$strSQL = "INSERT INTO ".PREFIX."_pages_media (id_PAG,id_MED) 
																	VALUES (".OPENDOWN_idPAG.",$imgID)";
														$objConn->i_query($strSQL);
													}
												}
											}
											catch(Exception $e)
											{
												$errUPLMSG = captcha($e);
												$strResp =  $errUPLMSG;
											}
										}
									}
									$strResp = 1;
								}
								else
								{
									$errUPLMSG = $arrTextes["errors"]["general"];
									$strResp =  $errUPLMSG;
								}
							}
							else
							{
								$strResp = 1;
							}
						}
						else
						{
							$errUPLMSG = $arrTextes["errors"]["general"];
							$strResp =  $errUPLMSG;
						}
					}
					catch(Exception $e)
					{
						$errUPLMSG = captcha($e);
						$strResp =  $errUPLMSG;
					}
				}
				else
				{
					if(file_exists($targetFile)) unlink($targetFile);
					$errUPLMSG = $arrTextes["errors"]["general"];
					$strResp =  $errUPLMSG;
				}	
			}
		}
		else
		{
			$errUPLMSG = $arrTextes["errors"]["wrongpara"]; // wrong parameter given to function
			$strResp =  $errUPLMSG;
		}
	}
	else
	{
		$errUPLMSG = $arrTextes["errors"]["wrongpara"]; // wrong parameter given to function
		$strResp =  $errUPLMSG;
	}
}
else
	$strResp = "EMPTY";
//if(!isset($noecho)) 
echo $strResp;
?>