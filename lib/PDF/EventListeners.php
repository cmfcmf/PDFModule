<?php

class PDF_EventListeners
{
    public function getContentTypes(Zikula_Event $event)
    {
        $types = $event->getSubject();
        $types->add('PDF_ContentType_QRCode');
    }
}
