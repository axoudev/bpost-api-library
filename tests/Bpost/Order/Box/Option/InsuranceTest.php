<?php
namespace Bpost;

use TijsVerkoyen\Bpost\Bpost\Order\Box\Option\Insurance;
use TijsVerkoyen\Bpost\Exception\LogicException\BpostInvalidValueException;

class InsuranceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create a generic DOM Document
     *
     * @return \DOMDocument
     */
    private static function createDomDocument()
    {
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;

        return $document;
    }

    /**
     * Tests Insurance->toXML
     */
    public function testToXML()
    {
        $expectedDocument = self::createDomDocument();
        $insured = $expectedDocument->createElement('insured');
        $insured->appendChild($expectedDocument->createElement('basicInsurance'));
        $expectedDocument->appendChild($insured);

        $actualDocument = self::createDomDocument();
        $insurance = new Insurance('basicInsurance');
        $actualDocument->appendChild(
            $insurance->toXML($actualDocument)
        );
        $this->assertEquals($expectedDocument, $actualDocument);

        $data = array(
            'insured' => array(
                'additionalInsurance' => array(
                    '@attributes' => array(
                        'value' => 3,
                    ),
                ),
            ),
        );

        $expectedDocument = self::createDomDocument();
        $insured = $expectedDocument->createElement('insured');
        $additionalInsurance = $expectedDocument->createElement('additionalInsurance');
        $additionalInsurance->setAttribute('value', $data['insured']['additionalInsurance']['@attributes']['value']);
        $insured->appendChild($additionalInsurance);
        $expectedDocument->appendChild($insured);

        $actualDocument = self::createDomDocument();
        $insurance = new Insurance(
            'additionalInsurance',
            $data['insured']['additionalInsurance']['@attributes']['value']
        );
        $actualDocument->appendChild(
            $insurance->toXML($actualDocument)
        );
        $this->assertEquals($expectedDocument, $actualDocument);

        $expectedDocument = self::createDomDocument();
        $insured = $expectedDocument->createElement('insured');
        $insured->appendChild($expectedDocument->createElement('basicInsurance'));
        $expectedDocument->appendChild($insured);

        $actualDocument = self::createDomDocument();
        $insurance = new Insurance('basicInsurance');
        $actualDocument->appendChild(
            $insurance->toXML($actualDocument)
        );
        $this->assertEquals($expectedDocument, $actualDocument);

        $data = array(
            'insured' => array(
                'additionalInsurance' => array(
                    '@attributes' => array(
                        'value' => 3,
                    ),
                ),
            ),
        );

        $expectedDocument = self::createDomDocument();
        $insured = $expectedDocument->createElement('foo:insured');
        $insured->appendChild($expectedDocument->createElement('foo:basicInsurance'));
        $expectedDocument->appendChild($insured);

        $actualDocument = self::createDomDocument();
        $insurance = new Insurance('basicInsurance');
        $actualDocument->appendChild(
            $insurance->toXML($actualDocument, 'foo')
        );
        $this->assertSame($expectedDocument->saveXML(), $actualDocument->saveXML());
    }

    /**
     * Test validation in the setters
     */
    public function testFaultyProperties()
    {
        try {
            new Insurance(str_repeat('a', 10));
            $this->fail('BpostInvalidValueException not launched');
        } catch (BpostInvalidValueException $e) {
            // Nothing, the exception is good
        } catch (\Exception $e) {
            $this->fail('BpostInvalidValueException not caught');
        }

        try {
            new Insurance('additionalInsurance', str_repeat('1', 10));
            $this->fail('BpostInvalidValueException not launched');
        } catch (BpostInvalidValueException $e) {
            // Nothing, the exception is good
        } catch (\Exception $e) {
            $this->fail('BpostInvalidValueException not caught');
        }

        // Exceptions were caught,
        $this->assertTrue(true);
    }
}
