package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
	"time"

	"github.com/gin-contrib/cors"
	"github.com/gin-gonic/gin"
	"github.com/google/uuid"
	"github.com/joho/godotenv"

	connect_db "example.com/golang-restfulapip/configs/database"
	migration "example.com/golang-restfulapip/migrations"
	routers "example.com/golang-restfulapip/routers"
)

func main() {
	// Load environment variables from .env file
	err := godotenv.Load()
	if err != nil {
		log.Fatal("Error loading .env file")
	}

	// Initialize database connection
	db := connect_db.ConnectDB()

	// Initialize Gin router
	router := gin.New()

	// Logging middleware to log every request
	router.Use(func(c *gin.Context) {
		// Generate a request ID
		requestID := uuid.New().String()

		// Create a new log file every day
		logFileName := fmt.Sprintf("./logs/%s.log", time.Now().Format("2006-01-02"))
		f, err := os.OpenFile(logFileName, os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
		if err != nil {
			log.Fatal(err)
		}
		defer f.Close()

		// Log to file
		log.SetOutput(f)
		start := time.Now()

		// Log request details with IP address and request ID
		log.Printf("ReqID: %s | Method: %s %s | IP: %s ", requestID, c.Request.Method, c.Request.URL.Path, c.ClientIP())
		c.Next()

		// Log response status
		end := time.Now()
		latency := end.Sub(start)
		log.Printf("ReqID: %s | Method: %s %s | ResponseStatus: %d | Latency: %s ", requestID, c.Request.Method, c.Request.URL.Path, c.Writer.Status(), latency.String())
	})

	// Add CORS middleware with default configuration (allow all origins)
	router.Use(cors.Default())

	// Define routes
	api := router.Group("/api")
	router.GET("/", func(c *gin.Context) {
		c.JSON(http.StatusOK, gin.H{
			"message": "HELLO GOLANG RESTFUL API.",
		})
	})

	// Set up collection routes
	routers.SetLotteryRoutes(api, db)

	// Run database migrations
	if err := migration.Migrate(db); err != nil {
		panic("Failed to migrate tables")
	}

	// Start HTTP server
	PORT := os.Getenv("PORT")
	port := fmt.Sprintf(":%v", PORT)
	fmt.Println("Server Running on Port", port)
	http.ListenAndServe(port, router)
}
