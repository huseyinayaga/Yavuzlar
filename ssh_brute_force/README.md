# SSH Brute Force Tool 🔐

Bu proje, Go ile geliştirilmiş bir SSH brute force aracıdır. Belirtilen kullanıcı adı ve şifre kombinasyonlarını kullanarak SSH bağlantısı denemeleri yapar.

## 🚀 Özellikler

✅ **CLI tabanlı kullanım**  
✅ **Tek kullanıcı/şifre veya wordlist desteği**  
✅ **Worker Pool ile paralel işleme**  
✅ **Hedef IP veya hostname belirleme**  
✅ **Komut satırı argümanları ile özelleştirme**  

---

## 📌 Kullanım
🔹 Tek kullanıcı ve tek şifre ile çalıştırma
go run main.go -h 192.168.1.1 -u admin -p password123

🔹 Wordlist dosyaları ile çalıştırma
go run main.go -h 192.168.1.1 -U wordlists/users.txt -P wordlists/passwords.txt

🔹 Paralel çalışan worker sayısını belirleme
go run main.go -h 192.168.1.1 -U wordlists/users.txt -P wordlists/passwords.txt -workers 20

🔹 Yanlış kullanımda hata mesajı alırsınız
go run main.go -h 192.168.1.1 -U users.txt
✅ Çıktı: Hata: Şifre için -p veya -P parametresinden biri gereklidir