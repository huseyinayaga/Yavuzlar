package main

import (
    "bufio"
    "fmt"
    "os"
    "strings"
)

type User struct {
    Username string
    Password string
    UserType string
}

var users = map[string]User{
    "admin":    {Username: "admin", Password: "admin", UserType: "admin"},
    "customer": {Username: "customer", Password: "customer", UserType: "customer"},
}

func main() {
    reader := bufio.NewReader(os.Stdin)
    for {
        fmt.Println("Giriş yapmak için 0 (Admin) veya 1 (Müşteri) veya q (Çıkış) giriniz:")
        input, _ := reader.ReadString('\n')
        input = strings.TrimSpace(input)

        if input == "0" {
            login("admin")
        } else if input == "1" {
            login("customer")
        } else if input == "q" {
            fmt.Println("Çıkış yapılıyor!")
            break
        } else {
            fmt.Println("Geçersiz seçim. Lütfen tekrar deneyiniz.")
        }
    }
}

func login(userType string) {
    reader := bufio.NewReader(os.Stdin)

    fmt.Print("Kullanıcı Adı: ")
    username, _ := reader.ReadString('\n')
    username = strings.TrimSpace(username)

    fmt.Print("Şifre: ")
    password, _ := reader.ReadString('\n')
    password = strings.TrimSpace(password)

    user, exists := users[username]
    if exists && user.Password == password && user.UserType == userType {
        logAttempt(username, "başarılı")
        fmt.Printf("Hoş geldin, %s! Yetki: %s\n", username, user.UserType)

        if user.UserType == "admin" {
            adminMenu()
        } else {
            customerMenu(username)
        }
    } else {
        logAttempt(username, "hatalı")
        fmt.Println("Hatalı giriş. Lütfen tekrar deneyiniz.")
    }
}

func logAttempt(username, status string) {
    f, err := os.OpenFile("log.txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0666)
    if err != nil {
        fmt.Println("Log dosyası açılamadı:", err)
        return
    }
    defer f.Close()

    logEntry := fmt.Sprintf("Kullanıcı: %s, Giriş Durumu: %s\n", username, status)
    if _, err := f.WriteString(logEntry); err != nil {
        fmt.Println("Log kaydedilemedi:", err)
    }
}

func adminMenu() {
    reader := bufio.NewReader(os.Stdin)

    for {
        fmt.Println("\nAdmin Menüsü:")
        fmt.Println("1. Müşteri Ekle")
        fmt.Println("2. Müşteri Sil")
        fmt.Println("3. Log Listele")
        fmt.Println("q. Çıkış")
        fmt.Print("Seçiminizi yapınız: ")
        choice, _ := reader.ReadString('\n')
        choice = strings.TrimSpace(choice)

        switch choice {
        case "1":
            addCustomer()
        case "2":
            removeCustomer()
        case "3":
            listLogs()
        case "q":
            return
        default:
            fmt.Println("Geçersiz seçim.")
        }
    }
}

func customerMenu(username string) {
    reader := bufio.NewReader(os.Stdin)

    for {
        fmt.Println("\nMüşteri Menüsü:")
        fmt.Println("1. Profil Görüntüle")
        fmt.Println("2. Şifre Değiştir")
        fmt.Println("q. Çıkış")
        fmt.Print("Seçiminizi yapınız: ")
        choice, _ := reader.ReadString('\n')
        choice = strings.TrimSpace(choice)

        switch choice {
        case "1":
            viewProfile(username)
        case "2":
            changePassword(username)
        case "q":
            return
        default:
            fmt.Println("Geçersiz seçim.")
        }
    }
}

func addCustomer() {
    reader := bufio.NewReader(os.Stdin)

    fmt.Print("Yeni Müşteri Kullanıcı Adı: ")
    username, _ := reader.ReadString('\n')
    username = strings.TrimSpace(username)

    if _, exists := users[username]; exists {
        fmt.Println("Bu kullanıcı adı zaten mevcut.")
        return
    }

    fmt.Print("Yeni Müşteri Şifre: ")
    password, _ := reader.ReadString('\n')
    password = strings.TrimSpace(password)

    users[username] = User{Username: username, Password: password, UserType: "customer"}
    fmt.Println("Müşteri başarıyla eklendi.")
}

func removeCustomer() {
    reader := bufio.NewReader(os.Stdin)

    fmt.Print("Silmek istediğiniz Müşteri Kullanıcı Adı: ")
    username, _ := reader.ReadString('\n')
    username = strings.TrimSpace(username)

    user, exists := users[username]
    if exists && user.UserType == "customer" {
        delete(users, username)
        fmt.Println("Müşteri başarıyla silindi.")
    } else {
        fmt.Println("Müşteri bulunamadı.")
    }
}

func listLogs() {
    file, err := os.ReadFile("log.txt")
    if err != nil {
        fmt.Println("Log dosyası açılamadı:", err)
        return
    }
    fmt.Println("Loglar:")
    fmt.Println(string(file))
}

func viewProfile(username string) {
    user, exists := users[username]
    if exists {
        fmt.Printf("Kullanıcı Adı: %s\n", user.Username)
        fmt.Printf("Kullanıcı Tipi: %s\n", user.UserType)
    } else {
        fmt.Println("Profil bulunamadı.")
    }
}

func changePassword(username string) {
    reader := bufio.NewReader(os.Stdin)

    fmt.Print("Yeni Şifre: ")
    newPassword, _ := reader.ReadString('\n')
    newPassword = strings.TrimSpace(newPassword)

    user, exists := users[username]
    if exists {
        user.Password = newPassword
        users[username] = user
        fmt.Println("Şifre başarıyla değiştirildi.")
    } else {
        fmt.Println("Kullanıcı bulunamadı.")
    }
}