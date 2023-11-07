<div id="dv_sideboard">
	<div id="dv_sideboard_intern">
        <section class="row help_thumb">
			<?php
            /*include HELP*/
            include_once("inc/layout_struktur/help_sideboard.inc.php");
            ?>
		</section>
        <?php
            /*
            echo "<section class=\"startpage_nws_roll\">";
            $tnwsfetch = 6;
            // fetch news
			$strSQL = "SELECT nws.id_NWS AS rowID, nwst.name, nwst.texto, (SELECT COUNT(id_NWS) FROM ".PREFIX."_news WHERE nwst.lan='$cmslan' AND nws.aktiv) AS tnws
						FROM ".PREFIX."_news AS nws JOIN ".PREFIX."_news_text AS nwst ON nws.id_NWS=nwst.id_NWS
						WHERE nwst.lan='$cmslan' AND nws.aktiv ORDER BY nws.uptime DESC LIMIT $tnwsfetch";
			$rs = $objConn->rs_query($strSQL);
			if ($rs->count() > 0)
			{
				echo "<h1>".$arrTextes["GRT"]["NEWS"]."</h1>";
				$iID = null;
				while($row = $rs->fetchObject())
				{
                    $tnws=$row->tnws;
					if($iID!=$row->rowID)
					{
						$iID=$row->rowID;
						$lnk = BASEURL_SUFFIX."/";
						if(FLATLINK)
							$lnk = BASEURL_SUFFIX."/$cmslan/nws/nws-$iID";		
						else
							$lnk = "?s=nws&p=$row->rowID&lan=$cmslan";
						echo "<article class=\"startpage_news\" id=\"nws_$iID\">";
						echo "<h2>$row->name</h2>";
						echo substr($row->texto, 0, 200)." <a href=\"$lnk\">[...]</a>";
						echo "</article>";
					}
				}
                if($tnws > $tnwsfetch)
                {
                    if(FLATLINK)
                        $lnk = BASEURL_SUFFIX."/$cmslan/nws/";
                    else
                        $lnk = "?s=nws&lan=$cmslan";
                    echo "<span class=\"morenws\"><a href=\"$lnk\">".$arrTextes["GRT"]["morenews"]."</a></span>";
                }
			}
            echo "</section>";
            */
        ?>
	</div>
</div>