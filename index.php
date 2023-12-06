<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regex on .use File</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <p>Upload a .use file to get a description.<p>
    <input type="file" id="fileInput" onchange="readFile()" />
    <div id="fileContent"></div>

    <script>
        function readFile() {
            const input = document.getElementById('fileInput');
            const fileContentElement = document.getElementById('fileContent');
            const file = input.files[0];

            const reader = new FileReader();

            reader.onload = function (e) {
                const content = e.target.result;

                // Extract all occurrences of the word "class" and the word after it
                const classPattern = /\bclass\b\s+(\w+)/gi;
                let classMatch;
                let output = "";

                // Iterate through each match of the classPattern
                while ((classMatch = classPattern.exec(content)) !== null){
                    const className = classMatch[1];
                    output += `<br>Class ${className}<br>`;

                    // Extract variables and their data types after "attributes"
                    const attributesPattern = /\battributes\b\s*((?:(?!end|operations)[\s\S])*?)\s*(end|operations)/gi;
                    
                    // Use a new RegExp instance for each class to reset lastIndex
                    const attributesRegex = new RegExp(attributesPattern.source, 'gi');
                    attributesRegex.lastIndex = classMatch.index;

                    const attributesMatches = attributesRegex.exec(content);

                    // Display variables and data types
                    if (attributesMatches){
                        const attributesContent = attributesMatches[1];
                        const variablesPattern = /\b(\w+)\s*:\s*(\w+)\s*;?\s*/gi;

                        let variableMatch;
                        while ((variableMatch = variablesPattern.exec(attributesContent)) !== null) {
                            const variableName = variableMatch[1];
                            const dataType = variableMatch[2];
                            output += `${dataType} ${variableName}<br>`;
                        }
                    } 

                    // Extract content after "operations" and before "end"
                    const operationsPattern = /\boperations\b\s*((?:(?!end)[\s\S])*?)\s*end/gi;
                    
                    // Use a new RegExp instance for each class to reset lastIndex
                    const operationsRegex = new RegExp(operationsPattern.source, 'gi');
                    operationsRegex.lastIndex = classMatch.index;

                    const operationsMatches = operationsRegex.exec(content);

                    // Display operations content
                    if (operationsMatches){
                        const operationsContent = operationsMatches[1];
                        output += `Operations:<br>${operationsContent}<br>`;
                    }
                }

                // Display class names, variables, and operations
                if (output !== ""){
                    fileContentElement.innerHTML = output;
                }else{
                    fileContentElement.textContent = "No class names, variables, or operations found.";
                }
            };
            
            reader.readAsText(file);
        }
    </script>
</body>
</html>
