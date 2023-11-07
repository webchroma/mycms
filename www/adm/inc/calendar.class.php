<?php

// PHP Calendar Class Version 1.4 (5th March 2001)
//  
// Copyright David Wilkinson 2000 - 2001. All Rights reserved.
// 
// This software may be used, modified and distributed freely
// providing this copyright notice remains intact at the head 
// of the file.
//
// This software is freeware. The author accepts no liability for
// any loss or damages whatsoever incurred directly or indirectly 
// from the use of this script. The author of this software makes 
// no claims as to its fitness for any purpose whatsoever. If you 
// wish to use this software you should first satisfy yourself that 
// it meets your requirements.
//
// URL:   http://www.cascade.org.uk/software/php/calendar/
// Email: davidw@cascade.org.uk

class Calendar
{
    /*
        Constructor for the Calendar class
    */
    function Calendar()
    {
    }
    
    
    /*
        Get the array of strings used to label the days of the week. This array contains seven 
        elements, one for each day of the week. The first entry in this array represents Sunday. 
    */
    function getDayNames()
    {
        return $this->dayNames;
    }
    

    /*
        Set the array of strings used to label the days of the week. This array must contain seven 
        elements, one for each day of the week. The first entry in this array represents Sunday. 
    */
    function setDayNames($names)
    {
        $this->dayNames = $names;
    }
    
    /*
        Get the array of strings used to label the months of the year. This array contains twelve 
        elements, one for each month of the year. The first entry in this array represents January. 
    */
    function getMonthNames()
    {
        return $this->monthNames;
    }
    
    /*
        Set the array of strings used to label the months of the year. This array must contain twelve 
        elements, one for each month of the year. The first entry in this array represents January. 
    */
    function setMonthNames($names)
    {
        $this->monthNames = $names;
    }
    
    
    
    /* 
        Gets the start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
      function getStartDay()
    {
        return $this->startDay;
    }
    
    /* 
        Sets the start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
    function setStartDay($day)
    {
        $this->startDay = $day;
    }
    
    
    /* 
        Gets the start month of the year. This is the month that appears first in the year
        view. January = 1.
    */
    function getStartMonth()
    {
        return $this->startMonth;
    }
    
    /* 
        Sets the start month of the year. This is the month that appears first in the year
        view. January = 1.
    */
    function setStartMonth($month)
    {
        $this->startMonth = $month;
    }
    
    
    /*
        Return the URL to link to in order to display a calendar for a given month/year.
        You must override this method if you want to activate the "forward" and "back" 
        feature of the calendar.
        
        Note: If you return an empty string from this function, no navigation link will
        be displayed. This is the default behaviour.
        
        If the calendar is being displayed in "year" view, $month will be set to zero.
    */
    function getCalendarLink($month, $year)
    {
        return "";
    }
    
    /*
        Return the URL to link to for a given date.
        You must override this method if you want to activate the date linking
        feature of the calendar.
        
        Note: If you return an empty string from this function, no navigation link will
        be displayed. This is the default behaviour.
    */
    function getDateLink($day, $month, $year)
    {
		return "";
    }


    /*
        Return the HTML for the current month
    */
    function getCurrentMonthView()
    {
        $d = getdate(time());
        return $this->getMonthView($d["mon"], $d["year"]);
    }
    

    /*
        Return the HTML for the current year
    */
    function getCurrentYearView()
    {
        $d = getdate(time());
        return $this->getYearView($d["year"]);
    }
    
    
    /*
        Return the HTML for a specified month
    */
    function getMonthView($month, $year)
    {
        return $this->getMonthHTML($month, $year);
    }
    

    /*
        Return the HTML for a specified year
    */
    function getYearView($year)
    {
        return $this->getYearHTML($year);
    }
    
    
    
    /********************************************************************************
    
        The rest are private methods. No user-servicable parts inside.
        
        You shouldn't need to call any of these functions directly.
        
    *********************************************************************************/


    /*
        Calculate the number of days in a month, taking into account leap years.
    */
    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
        {
            return 0;
        }
   
        $d = $this->daysInMonth[$month - 1];
   
        if ($month == 2)
        {
            // Check for leap year
            // Forget the 4000 rule, I doubt I'll be around then...
        
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                }
                else
                {
                    $d = 29;
                }
            }
        }
    
        return $d;
    }


    /*
        Generate the HTML for a given month
    */
    function getMonthHTML($m, $y, $showYear = 1)
    {
        $s = "";
        
        $a = $this->adjustDate($m, $y);
        $month = $a[0];
        $year = $a[1];        
        
    	$daysInMonth = $this->getDaysInMonth($month, $year);
    	$date = getdate(mktime(12, 0, 0, $month, 1, $year));
    	
    	$first = $date["wday"];
    	$monthName = $this->monthNames[$month - 1];
    	
    	$prev = $this->adjustDate($month - 1, $year);
    	$next = $this->adjustDate($month + 1, $year);
    	
    	if ($showYear == 1)
    	{
    	    $prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
    	    $nextMonth = $this->getCalendarLink($next[0], $next[1]);
    	}
    	else
    	{
    	    $prevMonth = "";
    	    $nextMonth = "";
    	}
    	
    	$header = $monthName . (($showYear > 0) ? " " . $year : "");
    	
    	$s .= "<table class=\"calendarmonth\">\n";
    	$s .= "<tr>\n";
    	$s .= "<td colspan=\"7\" class=\"calendarMainHeader\">" . (($prevMonth == "") ? "&nbsp;" : "<a href=\"$prevMonth\" class=\"button\">&lt;&lt;</a>&nbsp;&nbsp;")  . $header . (($nextMonth == "") ? "&nbsp;" : "&nbsp;&nbsp;<a href=\"$nextMonth\" class=\"button\">&gt;&gt;</a>")  . "</td>\n";
    	$s .= "</tr>\n";
    	
    	$s .= "<tr>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+1)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+2)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+3)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+4)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+5)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+6)%7] . "</td>\n";
    	$s .= "</tr>\n";
    	
    	// We need to work out what date to start at so that the first appears in the correct column
    	$d = $this->startDay + 1 - $first;
    	while ($d > 1)
    	{
    	    $d -= 7;
    	}

        // Make sure we know when today is, so that we can use a different CSS style
        $today = getdate(time());
    	
    	while ($d <= $daysInMonth)
    	{
    	    $s .= "<tr>\n";       
    	    
    	    for ($i = 0; $i < 7; $i++)
    	    {
        	    $class = ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) ? "calendarToday" : "calendarday";
				
				$id="";
				if($this->yearview)
					if($this->checkMySqlDate($d, $month, $year))
					{
						$class="calendarAktivday";
						$id = " id=\"".mktime(0,0,0,$month,$d,$year)."\"";
					}
    	        $s .= "<td class=\"$class\" align=\"center\" valign=\"middle\"$id>";       
    	        if ($d > 0 && $d <= $daysInMonth)
    	        {
					$link = $this->getDateLink($d, $month, $year);
    	            $s .= (($link == "") ? $d : "<a $link>$d</a>");
    	        }
    	        else
    	        {
    	            $s .= "&nbsp;";
    	        }
      	        $s .= "</td>\n";       
        	    $d++;
    	    }
    	    $s .= "</tr>\n";    
    	}
    	
    	$s .= "</table>\n";
    	
    	return $s;  	
    }
    
    /*
        Generate the HTML for a given year
    */
    function getYearHTML($year)
    {
        $s = "";
    	$prev = $this->getCalendarLink(0, $year - 1);
    	$next = $this->getCalendarLink(0, $year + 1);
        
        $s .= "<table class=\"calendaryear\" border=\"0\">\n";
        $s .= "<tr>";
    	$s .= "<td colspan=\"4\" class=\"calendarMainHeader\">" . (($prev == "") ? "&nbsp;" : "<a href=\"$prev\" class=\"button\">&lt;&lt;</a>&nbsp;")  . (($this->startMonth > 1) ? $year . " - " . ($year + 1) : $year) . (($next == "") ? "&nbsp;" : "&nbsp;<a href=\"$next\" class=\"button\">&gt;&gt;</a>")  . "</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(0 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(1 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(2 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(3 + $this->startMonth, $year, 0) ."</td>\n";
		$s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(4 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(5 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(6 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(7 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(8 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(9 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(10 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"monthboxes\" valign=\"top\">" . $this->getMonthHTML(11 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "</table>\n";
        
        return $s;
    }

    /*
        Adjust dates to allow months > 12 and < 0. Just adjust the years appropriately.
        e.g. Month 14 of the year 2001 is actually month 2 of year 2002.
    */
    function adjustDate($month, $year)
    {
        $a = array();  
        $a[0] = $month;
        $a[1] = $year;
        
        while ($a[0] > 12)
        {
            $a[0] -= 12;
            $a[1]++;
        }
        
        while ($a[0] <= 0)
        {
            $a[0] += 12;
            $a[1]--;
        }
        
        return $a;
    }

    /* 
        The start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
    var $startDay = 0;

    /* 
        The start month of the year. This is the month that appears in the first slot
        of the calendar in the year view. January = 1.
    */
    var $startMonth = 1;

    /*
        The labels to display for the days of the week. The first entry in this array
        represents Sunday.
    */
    var $dayNames = array("S", "M", "T", "W", "T", "F", "S");
    
    /*
        The labels to display for the months of the year. The first entry in this array
        represents January.
    */
    var $monthNames = array("January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December");
                            
                            
    /*
        The number of days in each month. You're unlikely to want to change this...
        The first entry in this array represents January.
    */
    var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	
	var $yearview = 0;
}


class MyCalendar extends Calendar{
	
	function SetMySqlDays($array)
	{
		$this->arrMySqlDays = $array;
	}

	function getCalendarLink($month, $year)
    {
		// Redisplay the current page, but with some parameters 
		// to set the new month and year 
		$s = getenv('SCRIPT_NAME');
		$d = getdate(time());
		$yearN = $d["year"];
		if ($year<$yearN)
		{
			$strR = ""; 
		}
		else
		{
			$strR = "$s?month=$month&year=$year";
		}
		if($strR!=""&&$this->yearview)
			$strR .= "&ansicht=1";
		return $strR;
    }

	function checkMySqlDate($day, $month, $year)
	{
		$ts = mktime(0,0,0,$month,$day,$year);
		if(in_array($ts,$this->arrMySqlDays))
			return 1;
		else
			return 0;
	}
	
	function getDateLink($day, $month, $year)
    {
        // Only link the first day of every month 
		$ab = 0;
		$ts = mktime(0,0,0,$month,$day,$year);
		$s = getenv('SCRIPT_NAME');
		if($this->yearview)
		{
			return "href=\"$s?month=$month&year=$year\"";
		}
		
		if(in_array($ts,$this->arrMySqlDays))
		{ 
			$classL = "close";
			$ab = 1;
		}
		else
		{
			$classL = "open";
		}	
		return "href=\"$s?ab=$ab&tm=$ts&month=$month&year=$year&ansicht=".$this->yearview."\" class=\"$classL\"";
    }

	function SetJahrView()
	{
		$this->yearview = 1;
	}
	
	function getDayList($ts)
	{
		global $cmslan;
		$strSQL = "SELECT 'cal' AS typ, cal.id_CAL AS id, calinfo.name 
					FROM ".PREFIX."_calendar AS cal LEFT JOIN ".PREFIX."_calendar_info AS calinfo ON cal.id_CAL=calinfo.id_CAL 
					WHERE cal.tm=$ts AND calinfo.lan='$cmslan'
					UNION
					SELECT nwspara.zona AS typ, cal.id_CAL AS id, nws.name 
					FROM ".PREFIX."_calendar AS cal LEFT JOIN ".PREFIX."_calendar_nws AS calnws ON cal.id_CAL=calnws.id_CAL 
					LEFT JOIN ".PREFIX."_news_text AS nws ON calnws.id_NWS=nws.id_NWS 
					LEFT JOIN ".PREFIX."_news AS nwspara ON nws.id_NWS=nwspara.id_NWS 
					WHERE cal.tm=$ts AND nws.lan='$cmslan'";
		//echo $strSQL;
		try
		{
			$objConn = MySQL::getIstance();
			$rs = $objConn->rs_query($strSQL);
			if ($rs->count() > 0)
			{
				foreach($rs AS $row)
				{
					$arrResp[] =  $row->id."#".$row->name."#".$row->typ;
				}
			}
			else
			{
				$arrResp = null;
			}
		}

		catch(Exception $e)
		{
			$arrResp[0] = null;
			if($debug)
				$arrResp[0] = captcha($e);
		}
		return $arrResp;
	}
	
	function getMonthViewHtml($m, $y, $showYear = 1)
	{
		$s = "";
        global $cmslan, $arrTextes;
        $a = $this->adjustDate($m, $y);
        $month = $a[0];
        $year = $a[1];        
        
    	$daysInMonth = $this->getDaysInMonth($month, $year);
    	$date = getdate(mktime(12, 0, 0, $month, 1, $year));
    	
    	$first = $date["wday"];
    	$monthName = $this->monthNames[$month - 1];
    	
    	$prev = $this->adjustDate($month - 1, $year);
    	$next = $this->adjustDate($month + 1, $year);
    	
    	if ($showYear == 1)
    	{
    	    $prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
    	    $nextMonth = $this->getCalendarLink($next[0], $next[1]);
    	}
    	else
    	{
    	    $prevMonth = "";
    	    $nextMonth = "";
    	}
    	
    	$header = $monthName . (($showYear > 0) ? " " . $year : "");
    	
    	$s .= "<div class=\"calendarmonth\">\n";
    	$s .= "<div class=\"calendarmonth_navi\">" . (($prevMonth == "") ? "&nbsp;" : "<a href=\"$prevMonth\" class=\"button\">&lt;&lt;</a>&nbsp;&nbsp;")  . $header . (($nextMonth == "") ? "&nbsp;" : "&nbsp;&nbsp;<a href=\"$nextMonth\" class=\"button\">&gt;&gt;</a>")  . "</div>\n";

		$d = 1;

        // Make sure we know when today is, so that we can use a different CSS style
        $today = getdate(time());
    	while ($d <= $daysInMonth)
    	{
			$ts = mktime(0,0,0,$month,$d,$year);
			$arr = $this->getDayList($ts);
			is_array($arr) ? $class="singleday_termine":$class="singleday";
			$class = ($d < $today["mday"] && $year == $today["year"] && $month == $today["mon"]) ? $class."_gone" : $class;
			$s .= "<div class=\"$class\">\n";
			
			$s .= "<div style=\"float:left\"><strong>$d</strong></div>";
			$s .= "<div style=\"float:right\">";
			$s .= "<a href=\"".THISMAINPAGENAME."_neu.php?tm=$ts\" class=\"new_calendar\"><img src=\"imago/new.png\" /></a>";
			$s .= "</div><br class=\"break\" />";
			
			if(is_array($arr))
			{
				$s .= "<ul>";
				foreach($arr AS $key=>$value)
				{
					$arr = explode("#",$value);
					$id = $arr[0];
					$name = $arr[1];
					$s .= "<li>".strtoupper($arrTextes["calendar"][$arr[2]])." ".$name;
					$s .= "<div class=\"buttons\">";
					if($arr[2]=="cal")
						$s .= "<a href=\"".THISMAINPAGENAME."_edit.php?id_CAL=$id\" class=\"new_calendar\"><img src=\"imago/page_edit.png\" /></a>&nbsp;";
					$msg=str_replace("#name",$name,$arrTextes["aktions"]["dodelete"]);
					$s .= "<a href=\"".THISMAINPAGENAME.".php?id=$id&akt=loes\" class=\"aktlink\" id=\"loes,$id,$name\"><img src=\"imago/page_delete.png\" width=\"15\" height=\"15\" alt=\"$msg\" title=\"$msg\" style=\"margin-left:15px\"/></a>";
					$s .= "</div>";
					$s .= "</li>";
				}
				$s .= "</ul>";
			}   
    	    $s .= "</div>\n";
			$d++;
    	}	 
    	$s .= "</div>\n";
    	return $s;
	}
}
?>