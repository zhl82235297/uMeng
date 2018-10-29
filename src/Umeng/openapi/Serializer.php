<?php

namespace Umeng\openapi;

interface Serializer
{
	public function supportedContentType();
	public function serialize($serializer);
}