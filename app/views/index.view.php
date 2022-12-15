
<?php $this->view("header") ?>
    <section class=" details container" style="height: fit-content">


        <h1 style="text-align: center;">Manager-App</h1>
        <h1 class="container" style="margin-left:15px ;">chart</h1>

        <div class="items">





            <div class="chart-container " style="margin:0 15px ;">

                <canvas id="line-chart"></canvas>

            </div>


            <!-- <div style="margin:10px">



                <button class="btn" style="width:100%;height: 70px ; margin-top: 20px;background-color: black;">grow
                    +1</button>
                <button class="btn" style="width:100%;height: 70px ; margin-top: 20px;background-color: black;">shrink
                    -1</button>
                <button class="btn" style="width:100%;height: 70px ; margin-top: 20px;background-color:red">Deleting
                    all</button>
                <button class="btn"
                    style="width:100%;height: 70px ; margin-top: 20px;background-color:red">Clear</button>

            </div> -->



        </div>





        <section class="container">

            <div class="RADIO">

                <form action="<?= ROOT ?>Home/mode" method="POST">

                    <div class="policy">

                        <p>Please choose one Mode:</p>


                        <div class="radio">

                            <input type="radio" <?= $mode == "manual" ? "checked" : "" ?> type="submit" id="choice-m-one" name="mode" value="manual" data-cont=".one" checked>
                            <label class="iii" for="choice-m-one">Manual Mode:</label>

                        </div>

                        <div class="radio">

                            <input type="radio" <?= $mode == "automatic" ? "checked" : "" ?> id="choice-m-two" name="mode" value="automatic" data-cont=".two">
                            <label for="choice-m-two">Automatic Mode:</label>

                        </div>
                        <button class="btn" style="padding-top: 10px" type="submit">Ok</button>

                    </div>



                </form>
            </div>

        </section>









        <!-- <ul class="tabs">






            <li class="active" data-cont=".one">Manual mode</li>
            <li data-cont=".two">Automatic mode</li>



            <li data-cont=".three">Configrartion</li>


        </ul> -->

        <div class="content" style="margin: 27px ;">



            <div class="one" data-cont=".one" style="width: 100%;">

                <form id="submitt" action="<?= ROOT ?>Home/manualConfig" method="POST">

                    <div class="progression" style="width: 50%;">
                        <p class="progress-text" style="margin-bottom: 10px;">resizing the memcache pool -Manual mode
                        </p>


                        <!-- <p class="progress-percentage">0%</p> -->

                        <div class="results">

                            <div class="result-box">

                                <div class="result-name">

                                    Number Of Instances
                                    <div class="before"><?= $numberOfInstances ?></div>

                                </div>

                                <div class="result-progress">
                                    <span data-progress="<?= diplayNumberOfInstances($numberOfInstances) ?>%"></span>

                                </div>

                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;margin-top: 15px;">

                            <button type="submit" name="shrink"  style="width:200px;align-self: center; "
                                    class="btn" id="btnDecrease">shrinking
                                -1</button>
                            <button type="submit" name="grow"  style="width:200px; align-self: center;"
                                    class="btn" id="btnIncrease">growing
                                +1</button>



                        </div>



                    </div>


                </form>

            </div>
            <div class="two" data-cont=".two">


                <form action="<?= ROOT ?>Home/autoConfig" method="POST">

                    <div class="config-cache">

                        <label for="cache-capcity">Max Miss Rate threshold:</label>


                        <div>

                            <input class="slider" type="range" value="<?= $maxMissRate ?>" name="maxMissRate" max="100" min="0" step="1"
                                   id="cache-capcity" oninput="slider_value()">

                            <span id="value"><?= $maxMissRate ?>%</span>

                        </div>


                    </div>

                    <div class="config-cache">

                        <label for="cache-capcity1">Min Miss Rate threshold:</label>


                        <div>
                            <input class="slider" type="range" value="<?= $minMissRate ?>" name="minMissRate" max="100" min="0" step="1"
                                   id="cache-capcity1" oninput="slider_value1()">

                            <span id="value1"><?= $minMissRate ?>%</span>

                        </div>


                    </div>

                    <div class="separator sep">


                        <button class="btn " type="submit">Ok</button>



                    </div>

                </form>

            </div>







        </div>

        <section class="config  container">
            <form action="<?= ROOT ?>Home/config" method="POST">


                <div class="config-cache">

                    <label for="cache-capcity4">Cache Capcity :</label>

                    <div>
                        <?php if(isset($cacheConfig) && is_array($cacheConfig)): ?>

                            <input class="slider" type="range" value="<?= $cacheConfig[0]->capacity ?>" name="capacity" max="4" min="1" step="0.01"
                                    id="cache-capcity4" oninput="slider_value4()">

                            <span id="value4"><?= $cacheConfig[0]->capacity ?>MB</span>
                        <?php else: ?>
                            <input class="slider" type="range" value="0" name="capacity" max="4" min="1" step="0.01"
                                   id="cache-capcity4" oninput="slider_value4()">

                            <span id="value4">1MB</span>
                        <?php endif; ?>

                    </div>


                </div>

                <div class="policy">

                    <p>Please choose one of the following replacement policies:</p>


                        <?php if(isset($cacheConfig) && is_array($cacheConfig)): ?>

                            <div class="radio">
                            <input <?= $cacheConfig[0]->policy == "random" ? "checked" : "" ?> type="radio" id="choice-one" name="policy" value="random">
                            <label class="iii" for="choice-one"> Random Replacement</label>


                    </div>

                    <div class="radio">

                        <input <?= $cacheConfig[0]->policy == "LRU" ? "checked" : "" ?>  type="radio" id="choice-two"  name="policy" value="LRU">
                        <label for="choice-two">Least Recently Used</label>
                    </div>
                </div>
                    <?php else: ?>
                        <div class="radio">
                            <input type="radio" id="choice-one" name="policy" value="random">
                            <label class="iii" for="choice-one"> Random Replacement</label>


                        </div>

                        <div class="radio">

                            <input type="radio" id="choice-two"  name="policy" value="LRU">
                            <label for="choice-two">Least Recently Used</label>
                        </div>
                        </div>
                    <?php endif; ?>



                <div class="separator sep">


                    <button class="btn" type="submit">Ok</button>

                </div>





            </form>

            <div class="icon">

                <img src="" alt="">

            </div>





        </section>


        <form class="content" style="display:flex ;justify-content: center; gap: 10px;padding: 30px; margin: 30px;" action="<?= ROOT ?>Home/AllNodesOps" method="POST">

            <button type="submit" name="delete" class="btn bbtn" style="height: 70px ; ">Deleting
                all</button>
            <button type="submit" name="clear" class="btn bbtn" style="height: 70px ; ">Clear</button>


        </form>


    </section>




<?php $this->view("footer") ?>