<?php

declare(strict_types=1);

namespace SAML2\XML\xenc;

use DOMElement;
use SAML2\XML\ds\KeyInfo;
use Webmozart\Assert\Assert;

/**
 * Class containing encrypted data.
 *
 * Note: <xenc:EncryptionProperties> elements are not supported.
 *
 * @package simplesamlphp/saml2
 */
class EncryptedData extends AbstractXencElement
{
    /** @var CipherData */
    protected $cipherData;

    /** @var string|null */
    protected $encoding;

    /** @var EncryptionMethod|null */
    protected $encryptionMethod;

    /** @var string|null */
    protected $id;

    /** @var KeyInfo|null */
    protected $keyInfo;

    /** @var string|null */
    protected $mimeType;

    /** @var string|null */
    protected $type;


    /**
     * EncryptedData constructor.
     *
     * @param CipherData $cipherData The CipherData object of this EncryptedData.
     * @param string|null $id The Id attribute of this object. Optional.
     * @param string|null $type The Type attribute of this object. Optional.
     * @param string|null $mimeType The MimeType attribute of this object. Optional.
     * @param string|null $encoding The Encoding attribute of this object. Optional.
     * @param EncryptionMethod|null $encryptionMethod The EncryptionMethod object of this EncryptedData. Optional.
     * @param KeyInfo|null $keyInfo The KeyInfo object of this EncryptedData. Optional.
     */
    public function __construct(
        CipherData $cipherData,
        ?string $id = null,
        ?string $type = null,
        ?string $mimeType = null,
        ?string $encoding = null,
        ?EncryptionMethod $encryptionMethod = null,
        ?KeyInfo $keyInfo = null
    ) {
        $this->setCipherData($cipherData);
        $this->setEncoding($encoding);
        $this->setID($id);
        $this->setMimeType($mimeType);
        $this->setType($type);
        $this->setEncryptionMethod($encryptionMethod);
        $this->setKeyInfo($keyInfo);
    }


    /**
     * Get the CipherData object.
     *
     * @return CipherData
     */
    public function getCipherData(): CipherData
    {
        return $this->cipherData;
    }


    /**
     * @param CipherData $cipherData
     */
    protected function setCipherData(CipherData $cipherData)
    {
        $this->cipherData = $cipherData;
    }


    /**
     * Get the value of the Encoding attribute.
     *
     * @return string|null
     */
    public function getEncoding(): ?string
    {
        return $this->encoding;
    }


    /**
     * @param string|null $encoding
     */
    protected function setEncoding(?string $encoding): void
    {
        Assert::nullOrNotEmpty($encoding, 'Encoding in <xenc:EncryptedData> cannot be empty.');
        $this->encoding = $encoding;
    }


    /**
     * Get the EncryptionMethod object.
     *
     * @return EncryptionMethod|null
     */
    public function getEncryptionMethod(): ?EncryptionMethod
    {
        return $this->encryptionMethod;
    }


    /**
     * @param EncryptionMethod|null $encryptionMethod
     */
    protected function setEncryptionMethod(?EncryptionMethod $encryptionMethod): void
    {
        $this->encryptionMethod = $encryptionMethod;
    }


    /**
     * Get the value of the Id attribute.
     *
     * @return string
     */
    public function getID(): string
    {
        return $this->id;
    }


    /**
     * @param string|null $id
     */
    protected function setID(?string $id): void
    {
        Assert::nullOrNotEmpty($id, 'Id in <xenc:EncryptedData> cannot be empty.');
        $this->id = $id;
    }


    /**
     * Get the KeyInfo object.
     *
     * @return KeyInfo|null
     */
    public function getKeyInfo(): ?KeyInfo
    {
        return $this->keyInfo;
    }


    /**
     * @param KeyInfo|null $keyInfo
     */
    protected function setKeyInfo(?KeyInfo $keyInfo): void
    {
        $this->keyInfo = $keyInfo;
    }


    /**
     * Get the value of the MimeType attribute.
     *
     * @return string
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }


    /**
     * @param string|null $mimeType
     */
    protected function setMimeType(?string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }


    /**
     * Get the value of the Type attribute.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }


    /**
     * @param string|null $type
     */
    protected function setType(?string $type): void
    {
        Assert::nullOrNotEmpty($type, 'Type in <xenc:EncryptedData> cannot be empty.');
        $this->type = $type;
    }


    /**
     * @inheritDoc
     */
    public static function fromXML(DOMElement $xml): object
    {
        Assert::same($xml->localName, 'EncryptedData');
        Assert::same($xml->namespaceURI, EncryptedData::NS);

        $cipherData = CipherData::getChildrenOfClass($xml);
        Assert::count($cipherData, 1, 'No or more than one CipherData element found in <xenc:EncryptedData>.');

        $encryptionMethod = EncryptionMethod::getChildrenOfClass($xml);
        Assert::maxCount(
            $encryptionMethod,
            1,
            'No more than one EncryptionMethod element allowed in <xenc:EncryptedData>.'
        );

        $keyInfo = KeyInfo::getChildrenOfClass($xml);
        Assert::maxCount($keyInfo, 1, 'No more than one KeyInfo element allowed in <xenc:EncryptedData>.');

        return new self(
            $cipherData[0],
            self::getAttribute($xml, 'Id', null),
            self::getAttribute($xml, 'Type', null),
            self::getAttribute($xml, 'MimeType', null),
            self::getAttribute($xml, 'Encoding', null),
            count($encryptionMethod) === 1 ? $encryptionMethod[0] : null,
            count($keyInfo) === 1 ? $keyInfo[0] : null
        );
    }


    /**
     * @inheritDoc
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        if ($this->id !== null) {
            $e->setAttribute('Id', $this->id);
        }

        if ($this->type !== null) {
            $e->setAttribute('Type', $this->type);
        }

        if ($this->mimeType !== null) {
            $e->setAttribute('MimeType', $this->mimeType);
        }

        if ($this->encoding !== null) {
            $e->setAttribute('Encoding', $this->encoding);
        }

        if ($this->encryptionMethod !== null) {
            $this->encryptionMethod->toXML($e);
        }

        if ($this->keyInfo !== null) {
            $this->keyInfo->toXML($e);
        }

        $this->cipherData->toXML($e);

        return $e;
    }
}
