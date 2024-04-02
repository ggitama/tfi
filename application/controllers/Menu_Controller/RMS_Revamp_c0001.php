<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        
    class RMS_Revamp_c0001 extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model(['User_model', 'RMS']);
            $this->load->helper('url');
            $this->load->library('pagination');
            access_login();
            $this->session_token = hash('sha256', $_SERVER['SCRIPT_NAME']);
            $this->data_session = data_session($this->session_token);
        }

        public function index()
        {
            $PER_PAGE = 10;

            $jabatan = $this->db->get_where('tb_user_transmart', ['username' => $this->data_session['username']])->row()->role_id;
            $menu = menus($jabatan);
            $data['nama'] = $this->data_session['nama'];
            // $data['rms'] = $this->RMS->get_data_rms($PER_PAGE, 1);

            // Initialize pagination configuration based on page data (total data and how data per page).
            // $config['base_url'] = site_url('your_controller/index');
            // $config['total_rows'] = $this->RMS->count_all_data_rms();
            // $config['uri_segment'] = 3;
            // $config['per_page'] = $PER_PAGE;
            $config = array(
                'base_url' => site_url('/Menu_Controller/RMS_Revamp_c0001'),
                'total_rows' => $this->RMS->count_all_data_rms(), // Implement count_all_data() in your model
                'per_page' => 10, // Number of records per page
                'uri_segment' => 3,
                // Add more pagination config options as needed
            );

            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $filter = $this->input->post();
            $data['rms'] = $this->RMS->get_filtered_data($config['per_page'], $page, $filter);
            $data['filter'] = $filter;

            $this->pagination->initialize($config, $filter);
    
            $last = $this->uri->total_segments();
            $record_num = '';
            $record_num2 = '';
            for ($i = 1; $i <= $last; $i++) {
                $record_num .= $this->uri->segment($i) . '/';
            }
    
            $menu_query = $this->db->get_where('tb_menu_transmart', ['file' => $record_num])->row_array();
            $id_menu = $menu_query['id_menu'];
            $id_role = $jabatan;

            $data['menus'] = $menu;
            $data['title'] = 'RMS Revamp';
            $data['menu_header'] = 'Dashboard';
            $data['main_menu'] = 'RMS Revamp';
    
            $this->load->view('template_dashboard/Header_v', $data);
            $this->load->view('Menu_View/RMS_Revamp_v0001', $data);
            $this->load->view('template_dashboard/Footer_v', $data);
        }

        public function export_csv_chunk()
        {
            // Set the filename
            $filename = 'analytics_rms_export.csv';
            $filepath = $filename;
    
            // Open the file for writing
            $fp = fopen($filepath, 'w');
    
            // Write headers to the CSV file
            $headers = array('Sales Date', 'Territory', 'Regional', 'Sitecode', 'Store', 'Div Code', 'Div Name', 'Dept Name', 'Dept Name 1', 'Cat Code', 'Cat Name', 'Fam Code', 'Fam Name', 'Sub Fame Name', 'Item Code', 'Product Name', 'Supplier ID', 'Supplier Name', 'Priority Media Type', 'SCC Name', 'Supplier Type', 'Transaction Type', 'Values', 'Total');
            fputcsv($fp, $headers);
    
            // Fetch and process data in chunks
            $chunkSize = 100; // Set the chunk size
            $offset = 0;
            $counter = 0;
    
            do {
                // Fetch data in chunks
                $data = $this->RMS->get_data_rms($chunkSize, $offset);
                // if (empty($data))
                // {
                //     return;
                // }
    
                // Process and append each row to the CSV file
                foreach ($data as $row) {
                    fputcsv($fp, (array)$row); // Assuming $row is an object
                }
    
                // Increment offset for the next chunk
                $offset += $chunkSize;
                $counter += 1;
            // } while (!empty($data));
            } while ($counter < 2);
    
            // Close the file handle
            fclose($fp);
    
            // Download the CSV file
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            readfile($filepath);
    
            // Optionally, delete the file after download
            unlink($filepath);
        }

        public function export_xlsx_chunk()
        {
            // Set the filename
            $filename = 'analytics_rms_export.xlsx';
            $filepath = $filename;
    
            // Create a new Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
    
            // Write headers to the XLSX file
            $headers = array('Sales Date', 'Territory', 'Regional', 'Sitecode', 'Store', 'Div Code', 'Div Name', 'Dept Name', 'Dept Name 1', 'Cat Code', 'Cat Name', 'Fam Code', 'Fam Name', 'Sub Fame Name', 'Item Code', 'Product Name', 'Supplier ID', 'Supplier Name', 'Priority Media Type', 'SCC Name', 'Supplier Type', 'Transaction Type', 'Values', 'Total');
            $columnIndex = 1;
            foreach ($headers as $header) {
                $sheet->setCellValueByColumnAndRow($columnIndex, 1, $header);
                $columnIndex++;
            }
    
            // Fetch and process data in chunks
            $chunkSize = 100; // Set the chunk size
            $offset = 0;
            $counter = 0;
    
            do {
                // Fetch data in chunks
                $data = $this->RMS->get_data_rms($chunkSize, $offset);
    
                // Process and append each row to the XLSX file
                $rowIndex = count($sheet->toArray()) + 1;
                foreach ($data as $row) {
                    $columnIndex = 1;
                    foreach ((array)$row as $value) {
                        $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
                        $columnIndex++;
                    }
                    $rowIndex++;
                }
    
                // Increment offset for the next chunk
                $offset += $chunkSize;
                $counter += 1;
            // } while (!empty($data));
            } while ($counter < 2);
    
            // Save the XLSX file
            $writer = new Xlsx($spreadsheet);
            $writer->save($filepath);
    
            // Download the XLSX file
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            readfile($filepath);
    
            // Optionally, delete the file after download
            unlink($filepath);
        }
        
        public function export_json_chunk()
        {
            // Set the filename
            $filename = 'analytics_rms_export.json';
            $filepath = $filename;
    
            // Open the JSON file for writing
            $fp = fopen($filepath, 'w');
    
            // Write the opening bracket for the JSON array
            fwrite($fp, '[');
    
            // Fetch and process data in chunks
            $chunkSize = 100; // Set the chunk size
            $offset = 0;
            $counter = 0;
            $firstChunk = true;
    
            do {
                // Fetch data in chunks
                $data = $this->RMS->get_data_rms($chunkSize, $offset);
    
                // Process and append each row to the JSON file
                foreach ($data as $row) {
                    if (!$firstChunk) {
                        // Add a comma before each chunk, except the first one
                        fwrite($fp, ',');
                    }
    
                    // Convert the row to JSON format and write to the file
                    fwrite($fp, json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                    $firstChunk = false;
                }
    
                // Increment offset for the next chunk
                $offset += $chunkSize;
                $counter += 1;
            // } while (!empty($data));
            } while ($counter < 2);
    
            // Write the closing bracket for the JSON array
            fwrite($fp, ']');
    
            // Close the file handle
            fclose($fp);
    
            // Download the JSON file
            header('Content-Type: application/json');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            readfile($filepath);
    
            // Optionally, delete the file after download
            unlink($filepath);
        }
    }