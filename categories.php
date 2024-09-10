<?php include('partials-front/menu.php');?>



    <!-- Categories Section Starts Here -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Items</h2>

            <?php

                //display all category that are active
                //sql query
                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";

                //execute
                $res = mysqli_query($conn, $sql);

                //count rows 
                $count = mysqli_num_rows($res);
                //check wether category available or not

                if($count>0)
                {
                    //category available
                    while($row=mysqli_fetch_assoc($res))
                    {
                        //get values 
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        ?>

                        <a href="<?php echo SITEURL; ?>category-items.php?category_id=<?php echo $id; ?>">

                            <div class="box-3 float-container">
                                <?php
                                    //check wether image is available or not
                                    if($image_name=="")
                                    {
                                        //display msg
                                        echo " <div class='error'>Image not available<?div> ";
                                    }
                                    else
                                    {
                                        //image available
                                        ?>
                                        <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name;?>" alt="Camera" class="img-responsive">
                                        <?php
                                    }
                                ?>
                                

                                <h3 class="float-text "><?php echo $title; ?></h3>
                            </div>
                            </a>
                        
                        <?php

                    }

                }
                else
                {
                    //category not available
                    echo "<div class='error'>Category not Found</div>";
                }
                ?>


            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Categories Section Ends Here -->


    <?php include('partials-front/footer.php');?>