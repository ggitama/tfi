<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-5">RMS</h2>
        </div>
        <div class="col">
            <form method="post" action="<?php echo site_url('/Menu_Controller/RMS_Revamp_c0001/'); ?>">
                <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                <div class="container">
                    <div class="row">
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="item_code" class="form-label">Item Code</label>
                            <input class="form-control" id="item_code" name="item_code" placeholder="Type to search...">
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="metrics" class="form-label">Metrics</label>
                            <input class="form-control" list="datalist_metrics" name="values" id="metrics" placeholder="Type to search...">
                            <datalist id="datalist_metrics">
                                <option value="Back Margin Amount">
                                <option value="COGS">
                                <option value="Commercial Margin Amount">
                                <option value="Gross Margin Amount">
                                <option value="Gross Sales">
                                <option value="Member Discount Amount">
                                <option value="Net Sales">
                                <option value="Promo Rafaksi Amount Reception Qty">
                                <option value="Promo Rafaksi Total Amount">
                                <option value="Sales Qty">
                                <option value="Total Discount Amount">
                                <option value="VAT">
                            </datalist>
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="category" class="form-label">Category</label>
                            <input class="form-control" list="datalist_category" id="category" name="cat_name" placeholder="Type to search...">
                            <datalist id="datalist_category">
                                <option value="PILLOW & BOLSTER">
                                <option value="ACCESSORIES">
                                <option value="AIR CONDITIONER">
                                <option value="ALCOHOL">
                                <option value="ANIMAL">
                                <option value="AQUA EQUIPMENT">
                            </datalist>
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="date" class="form-label">Date</label>
                            <input class="form-control" id="date" name="salesdate" placeholder="Type to search...">
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="sub-family" class="form-label">Sub-Family</label>
                            <input class="form-control" id="sub-family" name="sub_fam_code" placeholder="Type to search...">
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="regional" class="form-label">Regional</label>
                            <input class="form-control" list="datalist_regional" id="operationregional" placeholder="Type to search...">
                            <datalist id="datalist_regional">
                                <option value="JaBaNusra 1">
                                <option value="JaBaNusra 2">
                                <option value="JaBaNusra 3">
                                <option value="JaBaNusra 4">
                                <option value="Jakarta">
                                <option value="Kalimanatan">
                                <option value="Los Angeles">
                                <option value="Chicago">
                            </datalist>
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="sub-family" class="form-label">Supplier Code</label>
                            <input class="form-control" list="datalistOptions" id="sub-family" value="<?php echo isset($filter['suppid']) ? $filter['suppid'] : ''; ?>" name="suppid" placeholder="Type to search...">
                            <datalist id="datalistOptions">
                                <option value="San Francisco">
                                <option value="New York">
                                <option value="Seattle">
                                <option value="Los Angeles">
                                <option value="Chicago">
                            </datalist>
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="sub-family" class="form-label">Territory</label>
                            <input class="form-control" list="datalistOptions" name="territory" id="sub-family" placeholder="Type to search...">
                            <datalist id="datalistOptions">
                                <option value="San Francisco">
                                <option value="New York">
                                <option value="Seattle">
                                <option value="Los Angeles">
                                <option value="Chicago">
                            </datalist>
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="sub-family" class="form-label">Store Code</label>
                            <input class="form-control" name="sitecode" list="datalistOptions" id="sub-family" placeholder="Type to search...">
                            <datalist id="datalistOptions">
                                <option value="San Francisco">
                                <option value="New York">
                                <option value="Seattle">
                                <option value="Los Angeles">
                                <option value="Chicago">
                            </datalist>
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="sub-family" class="form-label">Business Unit</label>
                            <input class="form-control" list="datalistOptions" id="sub-family" name="div_name" placeholder="Type to search...">
                            <datalist id="datalistOptions">
                                <option value="San Francisco">
                                <option value="New York">
                                <option value="Seattle">
                                <option value="Los Angeles">
                                <option value="Chicago">
                            </datalist>
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="sub-family" class="form-label">Supplier Type</label>
                            <input class="form-control" list="datalistOptions" id="sub-family" name="supp_type" placeholder="Type to search...">
                            <datalist id="datalistOptions">
                                <option value="San Francisco">
                                <option value="New York">
                                <option value="Seattle">
                                <option value="Los Angeles">
                                <option value="Chicago">
                            </datalist>
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="sub-family" class="form-label">Department</label>
                            <input class="form-control" list="datalistOptions" id="sub-family"  name="dept_name" placeholder="Type to search...">
                            <datalist id="datalistOptions">
                                <option value="San Francisco">
                                <option value="New York">
                                <option value="Seattle">
                                <option value="Los Angeles">
                                <option value="Chicago">
                            </datalist>
                        </div>
                        <div class="col-4 col-lg-3 mb-2">
                            <label for="sub-family" class="form-label">Family</label>
                            <input class="form-control" list="datalistOptions" id="sub-family" name="fam_name" placeholder="Type to search...">
                        </div>
                    </div>
                    <div class="row">
                        <button type="submit">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 overflow-x-scroll">
            <div class="overflow-x-scroll">
                <table id="data-table" class="table table-bordered overflow-scroll-x">
                    <thead>
                        <tr>
                            <th class="cell_salesdate">Date</th>
                            <th class="cell_territory">Territory</th>
                            <th class="cell_operationregional">Regional</th>
                            <th class="cell_sitecode">Site Code</th>
                            <th class="cell_store">Store</th>
                            <th class="cell_div_code">Div Code</th>
                            <th class="cell_div_name">Business Unit</th>
                            <th class="cell_dept_name">Dept Name</th>
                            <th class="cell_dept_name_1">Dept Name 1</th>
                            <th class="cell_cat_code">Cat Code</th>
                            <th class="cell_cat_name">Cat Name</th>
                            <th class="cell_fam_code">Fam Code</th>
                            <th class="cell_fam_name">Fam Name</th>
                            <th class="cell_sub_fam_code">Sub Fam Code</th>
                            <th class="cell_sub_fame_name">Sub Fame Name</th>
                            <th class="cell_item_code">Item Code</th>
                            <th class="cell_product_name">Item</th>
                            <th class="cell_suppid">Supplier ID</th>
                            <th class="cell_suppname">Supplier Name</th>
                            <th class="cell_priority_media_type">Priority Media Type</th>
                            <th class="cell_sccname">SCC</th>
                            <th class="cell_supp_type">Supp Type</th>
                            <th class="cell_Transaction_Type">Transaction Type</th>
                            <th class="cell_values">Metrics</th>
                            <th class="cell_total">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rms as $row): ?>
                            <tr>
                                <td class="cell_salesdate"><?php echo $row->salesdate; ?></td>
                                <td class="cell_territory"><?php echo $row->territory; ?></td>
                                <td class="cell_operationregional"><?php echo $row->operationregional; ?></td>
                                <td class="cell_sitecode"><?php echo $row->sitecode; ?></td>
                                <td class="cell_store"><?php echo $row->store; ?></td>
                                <td class="cell_div_code"><?php echo $row->div_code; ?></td>
                                <td class="cell_div_name"><?php echo $row->div_name; ?></td>
                                <td class="cell_dept_name"><?php echo $row->dept_name; ?></td>
                                <td class="cell_dept_name_1"><?php echo $row->dept_name_1; ?></td>
                                <td class="cell_cat_code"><?php echo $row->cat_code; ?></td>
                                <td class="cell_cat_name"><?php echo $row->cat_name; ?></td>
                                <td class="cell_fam_code"><?php echo $row->fam_code; ?></td>
                                <td class="cell_fam_name"><?php echo $row->fam_name; ?></td>
                                <td class="cell_sub_fam_code"><?php echo $row->sub_fam_code; ?></td>
                                <td class="cell_sub_fame_name"><?php echo $row->sub_fame_name; ?></td>
                                <td class="cell_item_code"><?php echo $row->item_code; ?></td>
                                <td class="cell_product_name"><?php echo $row->product_name; ?></td>
                                <td class="cell_suppid"><?php echo $row->suppid; ?></td>
                                <td class="cell_suppname"><?php echo $row->suppname; ?></td>
                                <td class="cell_priority_media_type"><?php echo $row->priority_media_type; ?></td>
                                <td class="cell_sccname"><?php echo $row->sccname; ?></td>
                                <td class="cell_supp_type"><?php echo $row->supp_type; ?></td>
                                <td class="cell_Transaction_Type"><?php echo $row->Transaction_Type; ?></td>
                                <td class="cell_values"><?php echo $row->values; ?></td>
                                <td class="cell_total"><?php echo $row->total; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?php echo site_url('/Menu_Controller/RMS_Revamp_c0001/export_csv_chunk'); ?>" class="btn btn-success mb-3">Export CSV</a>
                <a href="<?php echo site_url('/Menu_Controller/RMS_Revamp_c0001/export_xlsx_chunk'); ?>" class="btn btn-warning mb-3">Export XLSX</a>
                <a href="<?php echo site_url('/Menu_Controller/RMS_Revamp_c0001/export_json_chunk'); ?>" class="btn btn-primary mb-3">Export JSON</a>
            </div>
            <?php echo $this->pagination->create_links(); ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $(".cell_sitecode, .cell_div_code, .cell_dept_name, .cell_dept_name_1, .cell_cat_code, .cell_cat_name, .cell_fam_code, .cell_fam_name, .cell_sub_fam_code, .cell_sub_fame_name, .cell_item_code, .cell_suppid, .cell_suppname, .cell_priority_media_type, .cell_supp_type, cell_Transaction_Type").hide();
        // DataTable initialization with Bootstrap styling
        $('#data-table').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true
        });
    });
</script>
