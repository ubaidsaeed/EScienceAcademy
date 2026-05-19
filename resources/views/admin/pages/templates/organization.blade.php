

<style>
  body {
    font-family: Arial, sans-serif;
    text-align: center;
    /*padding: 20px;*/
    background: #fff;
    overflow-x: hidden;
  }

  .flip-card {
    perspective: 1000px;
    width: 120px;    
    height: 120px;
  }
  .orgchart .node.focused{
    background-color: white !important;
  }
  .orgchart .node{
    background-color: white !important;
  }

  .flip-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.6s;
    transform-style: preserve-3d;
  }

  .flip-card:hover .flip-card-inner {
    transform: rotateY(180deg);
  }

  .flip-card-front,
  .flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border: 2px solid #1a4c8b;
    border-radius: 10px;
    background: #fff;
    padding: 6px;
    box-sizing: border-box;
  }

  /* Front (text) */
  .flip-card-front {
    color: #1a4c8b;
    font-weight: bold;
    font-size: clamp(10px, 1vw, 12px); /* responsive text size */
    text-align: center;
    line-height: 1.2em;
  }

  /* Back (photo) */
  .flip-card-back {
    transform: rotateY(180deg);
  }
  .flip-card-back img {
    /*width: 70%;*/
    width: 55%;
    max-width: 80px;
    height: auto;
    border-radius: 50%;
    object-fit: cover;
  }

  #chart-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
  }

  #chart-container {
    transform-origin: top center;
    transition: transform 0.3s ease-in-out; 
  }
</style>

<div id="chart-wrapper">
  <div id="chart-container"></div>
</div>

