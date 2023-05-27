import 'dart:io';
import 'dart:typed_data';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:pdf_text/pdf_text.dart';
import 'package:path_provider/path_provider.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  String path = 'path/to/your/pdf/file.pdf';
  var result = await processPdf(path);
  String newPath = result['newPdfPath'];
  String plainText = result['plainText'];

  print('Plain text:');
  print(plainText);

  // Save or display the new PDF, newPath.
}

Future<Map<String, String>> processPdf(String path) async {
  final pdfDoc = await PDFDoc.fromPath(path);
  int pageCount = pdfDoc.length;

  final newPdf = pw.Document();
  final font = pw.Font.helvetica();
  final fontSize = 12.0;

  StringBuffer plainTextBuffer = StringBuffer();

  for (int i = 1; i <= pageCount; i++) {
    final page = await pdfDoc.pageAt(i);
    final text = await page.text;

    plainTextBuffer.writeln(text);

    newPdf.addPage(
      pw.Page(
        build: (pw.Context context) {
          return pw.Padding(
            padding: const pw.EdgeInsets.all(8.0),
            child: pw.Text(
              text,
              style: pw.TextStyle(
                font: font,
                fontSize: fontSize,
              ),
            ),
          );
        },
      ),
    );
  }

  // Save the new PDF to a file
  Directory appDocDir = await getApplicationDocumentsDirectory();
  String newPdfPath = '${appDocDir.path}/new_pdf.pdf';
  File file = File(newPdfPath);
  Uint8List bytes = newPdf.save();
  await file.writeAsBytes(bytes);

  return {
    'newPdfPath': newPdfPath,
    'plainText': plainTextBuffer.toString(),
  };
}