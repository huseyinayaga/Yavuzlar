package main

import (
	"bufio"
	"fmt"
	"log"
	"net/http"
	"os"
	"path/filepath"
	"strings"

	"github.com/PuerkitoBio/goquery"
)

func main() {

	outputDir := "scraped_data"

	err := os.MkdirAll(outputDir, os.ModePerm)
	if err != nil {
		log.Fatal("Klasor olusturulamadi :", err)
	}

	reader := bufio.NewReader(os.Stdin)
	for {
		fmt.Println("\n--- Web Scraper Menü ---")
		fmt.Println("1: The Hacker News'ten veri çek ve kaydet")
		fmt.Println("2: Cyrops'tan veri çek ve kaydet")
		fmt.Println("3: Yavuzlardan veri çek ve kaydet")
		fmt.Println("q: Çıkış")
		fmt.Print("Bir seçenek girin: ")

		choice, _ := reader.ReadString('\n')
		choice = strings.TrimSpace(choice)

		switch choice {
		case "1":
			fmt.Println("\nThe Hacker News'ten veri çekiliyor...")
			scrapeAndSave("https://thehackernews.com/", filepath.Join(outputDir, "thehackernews.txt"))
		case "2":
			fmt.Println("\nCyrops'tan veri çekiliyor...")
			scrapeAndSave("https://cyrops.com/", filepath.Join(outputDir, "cyrops.txt"))
		case "3":
			fmt.Println("\nYavuzlar'dan veri çekiliyor...")
			scrapeAndSave("https://yavuzlar.com/", filepath.Join(outputDir, "yavuzlar.txt"))
		case "q":
			fmt.Println("\nÇıkış yapılıyor...")
			os.Exit(0)
		default:
			fmt.Println("\nGeçersiz seçenek, lütfen tekrar deneyin.")
		}
	}
}

func scrapeAndSave(url string, filePath string) {

	res, err := http.Get(url)
	if err != nil {
		log.Fatal(err)
	}
	defer res.Body.Close()

	doc, err := goquery.NewDocumentFromReader(res.Body)
	if err != nil {
		log.Fatal(err)
	}

	title := doc.Find("title").Text()
	if title == "" {
		title = "Başlık bulunamadı"
	}

	description, _ := doc.Find("meta[name='description']").Attr("content")
	if description == "" {
		description = "Açıklama bulunamadı"
	}

	date := doc.Find("time").Text()
	if date == "" {
		date = doc.Find("meta[name='publish-date']").AttrOr("content", "Tarih bulunamadı")
	}

	file, err := os.Create(filePath)
	if err != nil {
		log.Fatal(err)
	}
	defer file.Close()

	writer := bufio.NewWriter(file)
	fmt.Fprintf(writer, "Başlık: %s\n", title)
	fmt.Fprintf(writer, "Açıklama: %s\n", description)
	fmt.Fprintf(writer, "Tarih: %s\n", date)
	writer.Flush()

	fmt.Printf("Veriler %s dosyasına başarıyla kaydedildi.\n", filePath)
}
