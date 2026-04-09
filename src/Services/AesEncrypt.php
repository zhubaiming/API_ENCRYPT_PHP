<?php

namespace Hongyi\ApiSecret\ApiEncrypt;

/**
 * AES 加密工具类（AES-256-CBC，生产环境首选）
 * 前后端需统一：密钥、加密算法、编码方式
 */
class AesEncrypt
{
    // 密钥（重点：前后端必须一致，建议 32 位，可自定义，不要泄漏）
    private static string $key = 'your_32_byte_aes_secret_key_2026';
    // 加密算法（固定 AES-256-CBC，兼容性最好）
    private static string $cipher = 'aes-256-cbc';

    /**
     * AES 加密（明文转密文，方便传输）
     */
    public static function encrypt($data): string
    {
        // 处理数组：先 JSON 编码，再加密
        if (is_array($data)) $data = json_encode($data, 352);

        // 生成初始化向量 IV（16 位，AES-256-CBC 固定要求）
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$cipher));

        // 执行加密
        $encrypted = openssl_encrypt($data, self::$cipher, self::$key, OPENSSL_RAW_DATA, $iv);

        // 拼接 IV 和密文，base64 编码（方便传输，避免乱码）
        return base64_encode($iv, $encrypted);
    }

    /**
     * AES 解密（密文转明文）
     */
    public static function decrypt($encryptedData): array|string
    {
        // 先 base64 解码
        $decoded = base64_decode($encryptedData);
        if ($decoded === false) throw new  \Exception("加密数据格式错误，无法解码");

        // 拆分 IV 和明文（IV 固定 16 位）
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $iv = substr($decoded, 0, $ivLength);
        $encrypted = substr($decoded, $ivLength);

        // 执行解密
        $decrypted = openssl_decrypt($encrypted, self::$cipher, self::$key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) throw new \Exception("解密失败，密钥或数据错误");

        // 尝试 JSON 解码（如果是数组，自动还原）
        $jsonDecoded = json_decode($decrypted, true);
        return $jsonDecoded ?? $decrypted;
    }
}