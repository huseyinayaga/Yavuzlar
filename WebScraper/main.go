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
		fmt.Println("3: BBC'den veri çek ve kaydet")
		fmt.Println("q: Çıkış")
		fmt.Print("Bir seçenek girin: ")

		choice, _ := reader.ReadString('\n')
		choice = strings.TrimSpace(choice)

		switch choice {
		case "1":
			fmt.Println("\nThe Hacker News'ten veri çekiliyor...")
			scrapeHackerNews(filepath.Join(outputDir, "thehackernews.txt"))
		case "2":
			fmt.Println("\nCyrops'tan veri çekiliyor...")
			scrapeCyrops(filepath.Join(outputDir, "cyrops.txt"))
		case "3":
			fmt.Println("\nBBC'den veri çekiliyor...")
			scrapeBBC(filepath.Join(outputDir, "bbc.txt"))
		case "q":
			fmt.Println("\nÇıkış yapılıyor...")
			os.Exit(0)
		default:
			fmt.Println("\nGeçersiz seçenek, lütfen tekrar deneyin.")
		}
	}
}

func scrapeHackerNews(filePath string) {
	url := "https://thehackernews.com/"
	res, err := http.Get(url)
	if err != nil {
		log.Fatal(err)
	}
	defer res.Body.Close()

	doc, err := goquery.NewDocumentFromReader(res.Body)
	if err != nil {
		log.Fatal(err)
	}

	var data []string
	doc.Find(".body-post").Each(func(i int, s *goquery.Selection) {
		title := strings.TrimSpace(s.Find("h2.home-title").Text())
		description := strings.TrimSpace(s.Find(".home-desc").Text())
		time := strings.TrimSpace(s.Find(".item-label span.h-datetime").Text())

		if title == "" {
			title = "Başlık bulunamadı"
		}
		if description == "" {
			description = "Açıklama bulunamadı"
		}
		if time == "" {
			time = "Zaman bilgisi bulunamadı"
		}

		postData := fmt.Sprintf("Başlık: %s\nAçıklama: %s\nZaman: %s\n", title, description, time)
		data = append(data, postData)
	})

	saveToFile(filePath, data)
}

func scrapeCyrops(filePath string) {
	url := "https://cyrops.com/"
	res, err := http.Get(url)
	if err != nil {
		log.Fatal(err)
	}
	defer res.Body.Close()

	doc, err := goquery.NewDocumentFromReader(res.Body)
	if err != nil {
		log.Fatal(err)
	}

	var data []string
	doc.Find(".elementor-column").Each(func(i int, s *goquery.Selection) {
		title := strings.TrimSpace(s.Find(".elementor-widget-wrap .elementor-element .elementor-widget-container .tt-icon-box__content h3.tt-icon-box__title").Text())
		description := strings.TrimSpace(s.Find(".elementor-widget-wrap .elementor-element .elementor-widget-container .tt-icon-box__content p.tt-icon-box__description").Text())
		time := strings.TrimSpace(s.Find(".elementor-widget-wrap .elementor-element .elementor-widget-container .tt-icon-box__content .tt-icon-box__time").Text())
		// cyrops.com da zaman bilgisi bulunmuyor bu yüzden boş dönecektir.

		if title == "" {
			title = "Başlık bulunamadı"
		}
		if description == "" {
			description = "Açıklama bulunamadı"
		}
		if time == "" {
			time = "Zaman bilgisi bulunamadı"
		}
		postData := fmt.Sprintf("Başlık: %s\nAçıklama: %s\nZaman: %s\n", title, description, time)
		data = append(data, postData)

	})
	saveToFile(filePath, data)
}

func scrapeBBC(filePath string) {
	url := "https://bbc.com/turkce"
	res, err := http.Get(url)
	if err != nil {
		log.Fatal(err)
	}
	defer res.Body.Close()

	doc, err := goquery.NewDocumentFromReader(res.Body)
	if err != nil {
		log.Fatal(err)
	}
	var data []string
	doc.Find(".bbc-1rrncb9").Each(func(i int, s *goquery.Selection) {
		s.Find("li").Each(func(i int, s *goquery.Selection) {
			title := strings.TrimSpace(s.Find(".promo-text h3").Text())
			description := strings.TrimSpace(s.Find(".promo-text p").Text())
			time := strings.TrimSpace(s.Find(".promo-text time").Text())

			if title == "" {
				title = "Başlık bulunamadı"
			}
			if description == "" {
				description = "Açıklama bulunamadı"
			}
			if time == "" {
				time = "Zaman bilgisi bulunamadı"
			}
			postData := fmt.Sprintf("Başlık: %s\nAçıklama: %s\nZaman: %s\n", title, description, time)
			data = append(data, postData)
		})
	})
	saveToFile(filePath, data)
}

func saveToFile(filePath string, items []string) {
	file, err := os.Create(filePath)
	if err != nil {
		log.Fatal(err)
	}
	defer file.Close()

	writer := bufio.NewWriter(file)
	for _, item := range items {
		fmt.Fprintln(writer, item)
		fmt.Fprintln(writer, "------------------------------------------------------------")
	}
	writer.Flush()

	fmt.Printf("Veriler %s dosyasına başarıyla kaydedildi.\n", filePath)
}
