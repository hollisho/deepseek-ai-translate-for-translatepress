<?php

namespace hollisho\phpHelperTests\Unit;

use hollisho\helpers\SignatureValidatorHelper;
use PHPUnit\Framework\TestCase;

class SignatureValidatorTest extends TestCase
{
    /**
     * 测试使用MD5生成签名
     */
    public function testGenerateSignatureWithMD5()
    {
        $data = [
            'id' => '123',
            'name' => 'test',
            'amount' => '100.00'
        ];
        $key = 'secret_key';
        
        $signature = SignatureValidatorHelper::generateSignature($data, $key, 'MD5');
        
        // 手动计算预期的签名结果
        ksort($data);
        $string = 'amount=100.00&id=123&name=test&key=secret_key';
        $expectedSignature = strtoupper(md5($string));
        
        $this->assertEquals($expectedSignature, $signature);
    }
    
    /**
     * 测试使用HMAC-SHA256生成签名
     */
    public function testGenerateSignatureWithHMACSHA256()
    {
        $data = [
            'id' => '123',
            'name' => 'test',
            'amount' => '100.00'
        ];
        $key = 'secret_key';
        
        $signature = SignatureValidatorHelper::generateSignature($data, $key, 'HMAC-SHA256');
        
        // 手动计算预期的签名结果
        ksort($data);
        $string = 'amount=100.00&id=123&name=test&key=secret_key';
        $expectedSignature = strtoupper(hash_hmac('sha256', $string, $key));
        
        $this->assertEquals($expectedSignature, $signature);
    }
    
    /**
     * 测试验证有效签名 - MD5
     */
    public function testVerifyValidSignatureWithMD5()
    {
        $data = [
            'id' => '123',
            'name' => 'test',
            'amount' => '100.00'
        ];
        $key = 'secret_key';
        
        // 先生成签名
        $signature = SignatureValidatorHelper::generateSignature($data, $key, 'MD5');
        
        // 将签名添加到数据中
        $data['sign'] = $signature;
        
        // 验证签名
        $result = SignatureValidatorHelper::verifySignature($data, $key, 'MD5');
        
        $this->assertTrue($result);
    }
    
    /**
     * 测试验证有效签名 - HMAC-SHA256
     */
    public function testVerifyValidSignatureWithHMACSHA256()
    {
        $data = [
            'id' => '123',
            'name' => 'test',
            'amount' => '100.00'
        ];
        $key = 'secret_key';
        
        // 先生成签名
        $signature = SignatureValidatorHelper::generateSignature($data, $key, 'HMAC-SHA256');
        
        // 将签名添加到数据中
        $data['sign'] = $signature;
        
        // 验证签名
        $result = SignatureValidatorHelper::verifySignature($data, $key, 'HMAC-SHA256');
        
        $this->assertTrue($result);
    }
    
    /**
     * 测试验证无效签名
     */
    public function testVerifyInvalidSignature()
    {
        $data = [
            'id' => '123',
            'name' => 'test',
            'amount' => '100.00',
            'sign' => 'INVALID_SIGNATURE'
        ];
        $key = 'secret_key';
        
        $result = SignatureValidatorHelper::verifySignature($data, $key);
        
        $this->assertFalse($result);
    }
    
    /**
     * 测试没有签名字段的情况
     */
    public function testVerifySignatureWithoutSignField()
    {
        $data = [
            'id' => '123',
            'name' => 'test',
            'amount' => '100.00'
        ];
        $key = 'secret_key';
        
        $result = SignatureValidatorHelper::verifySignature($data, $key);
        
        $this->assertFalse($result);
    }
    
    /**
     * 测试空值和数组值被忽略
     */
    public function testEmptyAndArrayValuesAreIgnored()
    {
        $data = [
            'id' => '123',
            'name' => 'test',
            'empty' => '',
            'array' => ['a', 'b'],
            'amount' => '100.00'
        ];
        $key = 'secret_key';
        
        $signature = SignatureValidatorHelper::generateSignature($data, $key);
        
        // 手动计算预期的签名结果（不包含empty和array字段）
        $expectedData = [
            'id' => '123',
            'name' => 'test',
            'amount' => '100.00'
        ];
        ksort($expectedData);
        $string = 'amount=100.00&id=123&name=test&key=secret_key';
        $expectedSignature = strtoupper(md5($string));
        
        $this->assertEquals($expectedSignature, $signature);
    }
}