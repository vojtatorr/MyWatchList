<!-- Add part -->
<div class="container mt-4">
    <p>
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addPart" aria-expanded="false" aria-controls="addPart">
            Add part
        </button>
    </p>
    <div class="collapse" id="addPart">
        <div class="card card-body">

        <form action="editshow.php?id=<?= $id_show ?>" method="post" enctype="multipart/form-data">
                
                <!-- Hidden field for show ID -->
                <input type="hidden" name="id_show" value="<?= htmlspecialchars($id_show); ?>">

                <!-- Part Name input -->
                <div class="container m-2">
                    <label>Part Name </label>
                    <input type="text" name="part_name" required> <br>
                </div>

                <!-- OP input -->
                <div class="container m-2">
                    <label>OP </label>
                    <input type="text" name="op" required> <br>
                </div>

                <!-- ED input -->
                <div class="container m-2">
                    <label>ED </label>
                    <input type="text" name="ed" required> <br>
                </div>

                <!-- Number of Episodes input -->
                <div class="container m-2">
                    <label>Number of Episodes </label>
                    <input type="number" name="ep_num" required> <br>
                </div>

                <!-- Submit Button -->
                <input class="btn btn-primary my-2" type="submit" name="add" value="Add part" />
            </form>

            
        </div>
    </div>
</div>