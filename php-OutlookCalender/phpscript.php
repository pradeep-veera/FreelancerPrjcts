    <?php
    /**
     * Name         :   iCalEvent   
     * @category    :	iCalendar
     * @description :	Script to send an outlook calendar notice with each confirmation email.
     					$to_address : Email address.
     					$subject    : Subject of the event.
     					$startdateofevent  : Event start time.
     					$timeofevent       : Time of event (User friendly).
     					$description: Discription of the event
     					$location   : Location of the event

     * @version     :	1.0
    */

    function iCalEvent($to_address, $subject, $startdateofevent, $timeofevent, $description, $location)
    {
        $timestamp = date('Y/m/d\THis',strtotime("$timeofevent $startdateofevent"));

        //Create Email Headers
        $mime_boundary = "----Meeting Booking----".MD5(TIME());
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";
        
        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<body>\n";
        $message .= '<p>'.$description.'</p>';
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";

        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
        'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
        'VERSION:2.0' . "\r\n" .
        'METHOD:REQUEST' . "\r\n" .
        'BEGIN:VEVENT' . "\r\n" .
        'ATTENDEE;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
        'UID:'.date("Ymd\TGis", strtotime($timestamp)).rand(). "\r\n" .
        'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
        'DTSTART;TZID="Eastern Time":'.date("Ymd\THis", strtotime($timestamp)). "\r\n" .
        'DTEND:'. "\r\n" .
        'SUMMARY:' . $subject . "\r\n" .
        'LOCATION:' . $location . "\r\n" .
        'PRIORITY:5'. "\r\n" .
        'BEGIN:VALARM' . "\r\n" .
        'TRIGGER:-PT15M' . "\r\n" .
        'ACTION:DISPLAY' . "\r\n" .
        'DESCRIPTION:Reminder' . "\r\n" .
        'END:VALARM' . "\r\n" .
        'END:VEVENT'. "\r\n" .
        'END:VCALENDAR'. "\r\n";
        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;

        $mailsent = mail($to_address, $subject, $message, $headers);
    }



    /*

    	Sample date for testing the function.

    */       
    $to_address         = "JohnMark@example.com";       // Sender Email address.
    $startdateofevent   = "November 22nd 2016";         // Date of the Event.
    $timeofevent        = "10 AM";                      // Time of the Event.
    $subject            = "5K FUN RUN/WALK";            // Subject of the event   
    $description        = "The run will begin at 9:00 A.M. Participants must be registered by this point. We are expecting to have around 100 participants. We will have sent registration applications out prior to the event, to smaller running clubs in the area. Registration fees are $20. The fees are to help cover park permit fees, park participant fees, parking fees, and refreshments. If a participant has registered early they will have a parking pass distributed by us and the East Bay Regional Parks will be reimbursed by the end of the event. If a participant has not registered early they will not have a parking pass and will have to pay for parking on their own.
    ";        // Disciption of the Event
    $location = "Macdonald Trail, Redwood Regional Park and Anthony Chabot Regional Park";       // Location of the Event

    // Function call 
     iCalEvent($to_address, $subject, $startdateofevent, $timeofevent , $description, $location);
