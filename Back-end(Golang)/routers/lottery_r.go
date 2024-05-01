package routers

import (
	controllers "example.com/golang-restfulapip/controllers" // import controllers package
	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
)

func SetLotteryRoutes(router *gin.RouterGroup, db *gorm.DB) {
	ctrls := controllers.DBController{Database: db}

	router.GET("lotterys", ctrls.Getlottery)     // GET
	router.POST("lotterys", ctrls.Createlottery) // POST

}
