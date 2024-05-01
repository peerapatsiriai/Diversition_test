package controllers

import (
	"fmt"
	"math/rand"
	"net/http"
	"strconv"

	"example.com/golang-restfulapip/models"
	"github.com/gin-gonic/gin"
)

type LotteryRes struct {
	Prize1   string `json:"prize1"`
	Prize1_1 string `json:"prize1_1"`
	Prize1_2 string `json:"prize1_2"`
	Prize2_1 string `json:"prize2_1"`
	Prize2_2 string `json:"prize2_2"`
	Prize2_3 string `json:"prize2_3"`
}

// GET
func (db *DBController) Getlottery(c *gin.Context) {

	var lottery_list []models.Lotterys

	db.Database.Order("id desc").First(&lottery_list)

	if len(lottery_list) == 0 {

		c.JSON(http.StatusNotFound, gin.H{"message": "Not found."})
		return

	} else {

		lottery_int, err := strconv.Atoi(lottery_list[0].Prize1)
		if err != nil {
			fmt.Println(err)
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error."})
			return
		}
		lotteryRes := LotteryRes{
			Prize1:   lottery_list[0].Prize1,
			Prize1_1: formatNumber(lottery_int + 1),
			Prize1_2: formatNumber(lottery_int - 1),
			Prize2_1: lottery_list[0].Prize2_1,
			Prize2_2: lottery_list[0].Prize2_2,
			Prize2_3: lottery_list[0].Prize2_3,
		}

		c.JSON(http.StatusOK, gin.H{"results": &lotteryRes})
	}

}

// POST Create Lottery
func (db *DBController) Createlottery(c *gin.Context) {

	var lottery models.Lotterys

	lottery_int := rand.Intn(999)
	lottery.Prize1 = formatNumber(lottery_int)
	lottery.Prize2_1 = formatNumber(rand.Intn(999))
	lottery.Prize2_2 = formatNumber(rand.Intn(999))
	lottery.Prize2_3 = formatNumber(rand.Intn(999))

	result := db.Database.Create(&lottery)
	if result.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error."})
		return
	}

	// Reset format of loterry
	lotteryRes := LotteryRes{
		Prize1:   lottery.Prize1,
		Prize1_1: formatNumber(lottery_int + 1),
		Prize1_2: formatNumber(lottery_int - 1),
		Prize2_1: lottery.Prize2_1,
		Prize2_2: lottery.Prize2_2,
		Prize2_3: lottery.Prize2_3,
	}
	c.JSON(http.StatusCreated, gin.H{"results": &lotteryRes})
}

func formatNumber(num int) string {
	// Add 0 in number < 100
	if num < 100 {
		return fmt.Sprintf("%03d", num)
	}
	return strconv.Itoa(num)
}
