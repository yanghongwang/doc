using System;
using System.Text;
using System.Security.Cryptography;
using System.IO;

namespace TCSC.Common.Utils
{
    /// <summary>
    /// 在此描述Cryptography的说明
    /// </summary>
    internal class Cryptography
    {
        /// <summary>
        /// 默认密钥初始化向量
        /// </summary>
        internal static readonly byte[] DESIV = { 0x90, 0x78, 0xEF, 0x56, 0xCD, 0x34, 0xAB, 0x12 };
        /// <summary>
        /// 解密密钥
        /// </summary>
        internal static readonly string DecryptKey = "26185418";
        /// <summary>
        /// 0-9a-zA-Z表示62进制内的 0到61。
        /// </summary>
        internal static readonly string num62 = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        /// <summary>
        /// 支持的最小进制
        /// </summary>
        internal static int MIN_RADIX = 2;
        /// <summary>
        /// 支持的最大进制
        /// </summary>
        internal static int MAX_RADIX = 62;
        //短信点评最大登录次数
        public static long SMSCOMMIT_MAX_LOGINTIMES = System.Int64.MaxValue;
        //短信点评最小ID
        public static long SMSCOMMIT_MIN_ID = 20000000L;
    }



    /// <summary>
    /// AES 加密类
    /// </summary>
    public class AESEncryption
    {
        /***********************************************************************************************************
         * Rijndael IV参考值：
         *  { 0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF, 0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF }
         ***********************************************************************************************************/

        /// <summary>
        /// 默认密钥向量
        /// </summary>
        private static byte[] RijndaelIVValue = { 0x54, 0x43, 0x4D, 0x6F, 0x62, 0x69, 0x6C, 0x65, 0x5B, 0x41, 0x45, 0x53, 0x5F, 0x49, 0x56, 0x5D };    //Value:"TCMobile[AES_IV]"

        #region AES加密算法
        /// <summary>
        /// AES加密算法
        /// </summary>
        /// <param name="plainText">明文字符串</param>
        /// <param name="strRijnKey">密钥，必须为128位、192位或256位，默认选用128位</param>
        /// <returns>返回加密后的密文字节数组</returns>
        public static byte[] AESEncrypt(string plainText, string strRijnKey)
        {
            byte[] rijnKey = Encoding.UTF8.GetBytes(strRijnKey);
            byte[] rijnIV = RijndaelIVValue;
            return AESEncrypt(plainText, rijnKey, rijnIV);
        }

        /// <summary>
        /// AES加密算法
        /// </summary>
        /// <param name="plainText">明文字符串</param>
        /// <param name="strRijnKey">密钥，必须为128位、192位或256位，默认选用128位</param>
        /// <returns>返回加密后的string</returns>
        public static string AESEncryptForString(string plainText, string strRijnKey)
        {
           
            byte[] keyArray = UTF8Encoding.UTF8.GetBytes(strRijnKey);
            byte[] toEncryptArray = UTF8Encoding.UTF8.GetBytes(plainText);
            RijndaelManaged rDel = new RijndaelManaged();
            rDel.Key = keyArray;
            rDel.Mode = CipherMode.ECB;
            rDel.Padding = PaddingMode.PKCS7;
            ICryptoTransform cTransform = rDel.CreateEncryptor();
            byte[] resultArray = cTransform.TransformFinalBlock(toEncryptArray, 0, toEncryptArray.Length);
            return Convert.ToBase64String(resultArray, 0, resultArray.Length);
        }

        /// <summary>
        /// AES加密算法
        /// </summary>
        /// <param name="plainText">明文字符串</param>
        /// <param name="rijnKey">密钥字节数组，必须为128位、192位或256位，默认选用128位</param>
        /// <param name="rijnIV">密钥初始化向量字节数组</param>
        /// <returns>返回加密后的密文字节数组</returns>
        public static byte[] AESEncrypt(string plainText, byte[] rijnKey, byte[] rijnIV)
        {
            SymmetricAlgorithm rijndael = null;
            MemoryStream memStream = null;
            CryptoStream cryptoStream = null;
            try
            {
                rijndael = Rijndael.Create();
                if (string.IsNullOrEmpty(plainText))
                {
                    plainText = string.Empty;
                }
                byte[] inputByteArray = Encoding.UTF8.GetBytes(plainText);//得到需要加密的字节数组	
                //设置密钥及密钥向量
                rijndael.Key = rijnKey;
                rijndael.IV = rijnIV;
                memStream = new MemoryStream();
                cryptoStream = new CryptoStream(memStream, rijndael.CreateEncryptor(), CryptoStreamMode.Write);
                cryptoStream.Write(inputByteArray, 0, inputByteArray.Length);
                cryptoStream.FlushFinalBlock();
                byte[] cipherBytes = memStream.ToArray();//得到加密后的字节数组

                return cipherBytes;
            }
            catch (Exception)
            {
                throw;
            }
            finally
            {
                if (rijndael != null) rijndael.Clear();

                if (memStream != null)
                {
                    memStream.Flush();
                    memStream.Close();
                }

                if (cryptoStream != null)
                {
                    cryptoStream.Close();
                }
            }
        }
        #endregion

        #region AES解密算法
        /// <summary>
        /// AES解密算法
        /// </summary>
        /// <param name="cipherText">密文字节数组</param>
        /// <param name="strRijnKey">密钥，必须为128位、192位或256位，默认选用128位</param>
        /// <returns>返回解密后的字符串</returns>
        public static string AESDecrypt(byte[] cipherText, string strRijnKey)
        {
            byte[] rijnKey = Encoding.UTF8.GetBytes(strRijnKey);
            byte[] rijnIV = RijndaelIVValue;
            return AESDecrypt(cipherText, rijnKey, rijnIV);
        }

        /// <summary>
        /// AES解密算法
        /// </summary>
        /// <param name="cipherText">密文字节数组</param>
        /// <param name="rijnKey">密钥字节数组，必须为128位、192位或256位，默认选用128位</param>
        /// <param name="rijnIV">密钥初始化向量字节数组</param>
        /// <returns>返回解密后的字符串</returns>
        public static string AESDecrypt(byte[] cipherText, byte[] rijnKey, byte[] rijnIV)
        {
            SymmetricAlgorithm rijndael = null;
            MemoryStream memStream = null;
            CryptoStream cryptoStream = null;
            try
            {
                rijndael = Rijndael.Create();
                rijndael.Key = rijnKey;
                rijndael.IV = rijnIV;
                //byte[] decryptBytes = new byte[cipherText.Length];
                //memStream = new MemoryStream(cipherText);
                //cryptoStream = new CryptoStream(memStream, rijndael.CreateDecryptor(), CryptoStreamMode.Read);
                //cryptoStream.Read(decryptBytes, 0, decryptBytes.Length);

                //return decryptBytes;

                memStream = new MemoryStream(cipherText);
                cryptoStream = new CryptoStream(memStream, rijndael.CreateDecryptor(), CryptoStreamMode.Read);
                StreamReader streamReader = new StreamReader(cryptoStream);
                string decryptedString = streamReader.ReadToEnd();

                return decryptedString;
            }
            catch (Exception)
            {
                throw;
            }
            finally
            {
                if (rijndael != null) rijndael.Clear();

                if (memStream != null)
                {
                    memStream.Flush();
                    memStream.Close();
                }

                if (cryptoStream != null)
                {
                    cryptoStream.Close();
                }
            }
        }
        #endregion

        

    }

}
