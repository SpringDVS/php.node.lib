<?php

/* Notice:  Copyright 2016, The Care Connections Initiative c.i.c.
 * Author:  Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace SpringDvs;

class HttpService {
	/**
	 * Send a Dvsp packet via HTTP
	 * @param \SpringDvs\DvspPacket $frame
	 * @param string $address
	 * @return DvspPacket
	 */
	static public function sendPacket(DvspPacket $frame, $address) {
		
		$ch = curl_init();
		$serial = $frame->serialise();
	
		curl_setopt($ch, CURLOPT_URL,            $address);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_POST,           1 );
		curl_setopt($ch, CURLOPT_POSTFIELDS,      bin2hex($serial));
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: application/octet-stream')); 

		$bytes = curl_exec($ch);
		return DvspPacket::deserialise($bytes);
	}
	
	/**
	 * Receive a Packet from HTTP connection
	 * 
	 * @return SpringDvs\DvspPacket The packet that is sent
	 */
	static public function recvPacket() {
		$bytes = HttpService::recvRaw();
		return DvspPacket::deserialise($bytes);
	}
	
	static public function recvRaw() {
		return file_get_contents('php://input');
	}
}