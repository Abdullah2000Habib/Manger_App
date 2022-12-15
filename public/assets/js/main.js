let tabs = document.querySelectorAll(".tabs li");
let tabsArray = Array.from(tabs);

let divs = document.querySelectorAll(".content > div");
let divsArray = Array.from(divs);

let choices = document.querySelectorAll(".policy .radio input");

choices.forEach((ele) => {
  ele.addEventListener("click", function (e) {
    divsArray.forEach((div) => {
      div.style.display = "none";
    });

    divsArray.forEach((div) => {
      if (div.dataset.cont === e.currentTarget.dataset.cont) {
        div.style.display = "block";
      } else {
        div.style.display = "none";
      }
    });
  });
});

// //////////////////////////////////////////////////////////////////////////

// tabsArray.forEach((ele) => {
//   ele.addEventListener("click", function (e) {
//     tabsArray.forEach((ele) => {
//       ele.classList.remove("active");
//     });

//     e.currentTarget.classList.add("active");

//     divsArray.forEach((div) => {
//       div.style.display = "none";
//     });

//     document.querySelector(e.currentTarget.dataset.cont).style.display =
//       "block";

//   });
// });

// const randomValue = [];
// for (let i = 0; i <= 29; i++) {
//   randomValue.push(Math.ceil(Math.random() * 100));
// }
// console.log(randomValue);

//###########################



fetch("http://localhost/manager-app/public/data.json ")
  .then((response) => response.json())
  .then((data) => {

    let startTime =data.startTime;

    let days = startTime.split(' ')[0];
    let times = startTime.split(' ')[1];
    let hours =  parseInt(times.split(':')[0]) ;
    let minuts = parseInt(times.split(':')[1]);
    // console.log(days) 
    // console.log(times) 
    // console.log(hours) 
    // console.log(minuts) 
 
    for(let i = 0; i < 30; i++){


            let fullTime = ` ${hours<10? "0"+hours:hours}:${minuts<10? "0"+minuts:minuts}`
            let fullTimeFull= `${days} ${hours<10? "0"+hours:hours}:${minuts<10? "0"+minuts:minuts}`
            realTime.push(fullTimeFull);
            
 




      labels.push(fullTime)
     console.log(fullTime)
      if(minuts <59) {

        minuts++;


      }else {
        minuts=0;
        hours++;



      }
     
      




    }

   let realData =Array.from(Object.keys(data));

    realTime.map((item)=>{

    let garrIndex =0;
      if(realData.includes(item)){

       let arrIndex = realData.indexOf(item);
      garrIndex=arrIndex;
    

       



        arrnumberOfWorkers.push(data[realData[arrIndex]].numberOfWorkers || 0)
        arrmissRate.push(data[realData[arrIndex]].missRate || 0)
        arrhitRate.push(data[realData[arrIndex]].hitRate || 0 )
        arrnumberOfItems.push(data[realData[arrIndex]].NumberOfItems || 0 )
        arrtotalSize.push(data[realData[arrIndex]].Size  || 0)
        arrnumberOfRequests.push(data[realData[arrIndex]].NumberOfRequests || 0 )

















      }else{

        arrnumberOfWorkers.push(data[realData[garrIndex]].numberOfWorkers || 0)
        arrmissRate.push(0)
        arrhitRate.push(0)
        arrnumberOfItems.push(0 )
        arrtotalSize.push(0 )
        arrnumberOfRequests.push(0 )




      }

      

      


    })


    console.log(arrnumberOfWorkers)
    console.log(arrmissRate)
    console.log(arrhitRate)
    console.log(arrnumberOfItems)
    console.log(arrtotalSize)
    console.log(arrnumberOfRequests)





    
    


 

  });








let realTime = [];

























  // .then((data) => {
    
  //   Array.from(Object.keys(data)).map((item,index)=>{

  //     if (index!==0) {

  //             labels.push(item)
  //             console.log(item)
  //     }



  //   })


 

  // });


var labels = [

];

let arrnumberOfWorkers = [];
let arrmissRate = [];
let arrhitRate = [];
let arrnumberOfItems = [];
let arrtotalSize = [];
let arrnumberOfRequests = [];


let numberOfWorkers = {
  label: "Number Of Workers",
  backgroundColor: "transparent",
  borderColor: "rgb(255, 0, 0)",
  data:arrnumberOfWorkers ,
};

let missRate = {
  label: "Miss Rate",
  backgroundColor: "transparent",
  borderColor: "rgb(248, 0, 215)",
  data: arrmissRate,
};

let hitRate = {
  label: "Hit Rate",
  backgroundColor: "transparent",
  borderColor: "rgb(51, 255, 0)",
  data:arrhitRate,
};

let numberOfItems = {
  label: "Number Of Items",
  backgroundColor: "transparent",
  borderColor: "rgb(0, 0, 255)",
  data:arrnumberOfItems,
};
let totalSize = {
  label: "Total Size",
  backgroundColor: "transparent",
  borderColor: "rgb(0, 217, 255)",
  data: arrtotalSize,
};
let numberOfRequests = {
  label: "Number Of Requests",
  backgroundColor: "transparent",
  borderColor: "rgb(250, 212, 0)",
  data:arrnumberOfRequests,
};

const data = {
  labels: labels,
  datasets: [
    numberOfWorkers,
    missRate,
    hitRate,
    numberOfItems,
    totalSize,
    numberOfRequests,
  ],
};

const config = {
  type: "line",
  data: data,
  options: {
    maintainAspectRatio: false,
    legend: {
      labels: {
        fontColor: "black",
        fontSize: 13,
        fontWeight: "bold",
      },
    },
    scales: {
      yAxes: [
        {
          ticks: {
            fontColor: "black",
            fontWeight: "bold",
          },
          gridLines: {
            color: "#a8afefff",
            lineWidth: 2,
          },
        },
      ],
      xAxes: [
        {
          ticks: {
            fontColor: "black",
            fontWeight: "bold",
          },
          gridLines: {
            color: "#a8afefff",
            lineWidth: 2,
          },
        },
      ],
    },
  },
};

const myChart = new Chart(document.getElementById("line-chart"), config);

//###########################

function slider_value() {
  let slider = document.getElementById("cache-capcity");
  let value = document.getElementById("value");
  value.innerHTML = `${slider.value} %`;
}

function slider_value1() {
  let slider1 = document.getElementById("cache-capcity1");
  let value1 = document.getElementById("value1");
  value1.innerHTML = `${slider1.value} %`;
}
// function slider_value2() {
//   let slider2 = document.getElementById("cache-capcity2");
// let value2 = document.getElementById("value2");
//   value2.innerHTML = `${slider2.value} `;
// }

// function slider_value3() {
//   let slider3 = document.getElementById("cache-capcity3");
// let value3 = document.getElementById("value3");
//   value3.innerHTML = `${slider3.value} `;
// }

function slider_value4() {
  let slider4 = document.getElementById("cache-capcity4");
  let value4 = document.getElementById("value4");
  value4.innerHTML = `${slider4.value} MB `;
}

function reset_btn() {
  let slider4 = document.getElementById("cache-capcity4");
  let value4 = document.getElementById("value4");
  value4.innerHTML = `1 MB`;
}

let allSkills = document.querySelectorAll(".result-box .result-progress span");
let before = document.querySelectorAll(".result-box .result-name .before");

allSkills.forEach((span, index) => {
  span.style.width = span.dataset.progress;
  before[index].innerHTML = parseFloat(span.dataset.progress) / 12.5;
});
// function inc(e) {
//   e.preventDefault();
//   span.dataset.progress = parseFloat(span.dataset.progress) + 12.5 + "%";
// }




