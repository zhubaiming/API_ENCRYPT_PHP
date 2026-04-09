<?php

namespace Hongyi\ApiSecret\ApiEncrypt;

/**
 * HMAC 签名工具类（防篡改，支持 GET/POST 请求）
 * 前后端需统一：签名密钥、参数排序规则、签名算法
 */
class HmacSign
{
    // 签名密钥（重点：前后端必须一致，不要外泄，和 AES 密钥可不同）
    private static string $secret = 'your_hmac_secret_key_2026';
    // 签名算法（固定 SHA256，安全性高）
    private static string $algorithm = 'sha256';

    /**
     * 生成签名
     */
    public static function createSign($params): string
    {
        // 移除 sign 字段（避免重复签名）
        unset($params['sign']);

        // 按参数名 ASCII 排序（前后端必须一致，否则签名不一致）
        ksort($params);

        // 拼接参数为字符串（key=value&key=value）
//        $paramStr = http_build_query($params);
        $paramStr = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        // 生成 HMAC 签名（密钥 + 参数字符串，SHA256 算法）
        return hash_hmac(self::$algorithm, $paramStr, self::$secret);
    }

    /**
     * 验证签名
     */
    public static function verifySign($params): bool
    {
        // 判断 sign 字段是否存在
        if (!isset($params['sign']) || empty($params['sign'])) return false;

        // 保存接收到签名，然后移除 sign 字段
        $receivedSign = $params['sign'];
        unset($params['sign']);

        // 重新生成签名，和接收到的签名做对比
        $generatedSign = self::createSign($params);
        return $generatedSign === $receivedSign;
    }
}