<?php 
    $textFieldRequiredText = "Skal udfyldes";
    $selectFieldRequiredText = "Der skal vælges en mulighed";
?>

<div id="add-booking-section" class="h-100 flex-column" style="display: none">
    <h3 class="font-1 lh-1 fw-bold fs-1 mb-3 ">Tilføj booking</h3>
    <div class="success_msg toast align-items-center w-100 shadow-none mb-3 border border-success rounded-0 my-4"
        role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex p-2">
            <div
                class="toast-body f-18 d-flex flex-row gap-3 align-items-center text-success">
                <i class="fa-solid fa-check f-36 text-success"></i>
                Your Message Successfully Send.
            </div>
            <button type="button"
                class="me-2 m-auto bg-transparent border-0 ps-1 pe-0 text-success"
                data-bs-dismiss="toast" aria-label="Close"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
    </div>
    <div class="error_msg toast align-items-center w-100 shadow-none border-danger mb-3 my-4 border rounded-0"
        role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex p-2">
            <div
                class="toast-body f-18 d-flex flex-row gap-3 align-items-center text-danger">
                <i class="fa-solid fa-triangle-exclamation f-36 text-danger"></i>
                Der skete en fejl.
            </div>
            <button type="button"
                class="me-2 m-auto bg-transparent border-0 ps-1 pe-0 text-danger"
                data-bs-dismiss="toast" aria-label="Close"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
    </div>
    <form id="bookingForm" method="post" action="booking/create_booking.php" data-user-id="<?php echo $user_id; ?>"
        class="d-flex flex-column h-100 justify-content-center w-100 needs-validation form add-booking"
        novalidate>
        <div class="row">	
        <div class="mb-3 col-md-6">
            <label for="place" class="form-field-label">Sted</label>
            <input type="text" class="form-control py-2 px-4" name="place" id="place" placeholder="Indtast sted" required>
            <div class="invalid-feedback">
                <?php echo $textFieldRequiredText; ?>
            </div>
        </div>
        <div class="mb-3 col-md-6">
            <label for="date" class="form-field-label">Dato</label>
            <input type="date" class="form-control py-2 px-4" id="date" name="date" placeholder="Dato" required>
            <div class="invalid-feedback">
                <?php echo $textFieldRequiredText; ?>
            </div>
        </div>
        <div class="mb-3 col-md-6">
            <label for="time" class="form-field-label">Starttid</label>
            <select class="form-control py-2 px-4" id="time" name="time" placeholder="Tid" required>
                <option value="">Vælg starttid</option>
                <?php 
                    foreach($times as $time){
                        echo "<option value='$time->time_id'>$time->time_value</option>"; 
                    }            
                ?>
            </select>
            <div class="invalid-feedback">
                <?php echo $selectFieldRequiredText; ?>
            </div>
        </div>
        <div class="mb-3 col-md-6">
            <label for="hours" class="form-field-label">Antal timer</label>
            <input type="number" class="form-control py-2 px-4" id="hours" name="hours" placeholder="Indtast antal timer" required>
            <div class="invalid-feedback">
                <?php echo $textFieldRequiredText; ?>
            </div>
        </div>
        <div class="mb-3 col-md-6">
            <label for="shift" class="form-field-label">Stilling</label>
            <select class="form-control py-2 px-4" id="shift" name="shift" required>
                <option value="">Vælg stilling</option>
                <?php 
                    foreach($shifts as $shift){
                        echo "<option value='$shift->shift_id'>$shift->shift_name</option>"; 
                    }            
                ?>
            </select>
            <div class="invalid-feedback">
                <?php echo $selectFieldRequiredText; ?>
            </div>
        </div>
        <div class="mb-3 col-md-6">
            <label for="qualification" class="form-field-label">Uddannelse</label>
            <select class="form-control py-2 px-4" id="qualification" name="qualification" required>
                <option value="">Vælg uddannelse</option>
                <?php 
                    foreach($qualifications as $qualification){
                        echo "<option value='$qualification->qualification_id'>$qualification->qualification_name</option>"; 
                    }            
                ?>
            </select>
            <div class="invalid-feedback">
                <?php echo $selectFieldRequiredText; ?>
            </div>
        </div>
        <div class="mb-0 col-md-6">
            <select id="employee" name="employee" style="display:none">
                <option value="">Vælg vikar</option>
                <?php 
                    foreach($employees as $employee){
                        echo "<option value='$employee->id'>$employee->name</option>"; 
                    }            
                ?>
            </select>
        </div>        
        <div class="add-booking-buttons-wrapper">
            <div class="add-booking-buttons">
                <button type="button" id="add-booking-cancel-button" class="cancel-button">
                    Fortryd
                </button>
                <button type="button" id="add-booking-submit-button" class="action-button">
                    Tilføj
                </button>
            </div>
        </div>
        </div>
    </form>
</div>