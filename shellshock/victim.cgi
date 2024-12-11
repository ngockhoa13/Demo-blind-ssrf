#!/usr/local/bash-4.3.0/bin/bash
echo "Content-type: text/html"
export PATH="/usr/local/bin:/usr/bin:/bin:/usr/local/sbin:/usr/sbin:/sbin"
echo ""

echo "<html>"
echo "<head><title>Bash ShellShock</title></head>"
echo "<body>"

echo "<p>Executing command from User-Agent...</p>"

if [[ -n "$HTTP_USER_AGENT" ]]; then
    echo "<p>Debug User-Agent: $HTTP_USER_AGENT</p>"

    # Clean up User-Agent to prevent special characters from causing issues
    sanitized_user_agent=$(echo "$HTTP_USER_AGENT" | sed 's/[^a-zA-Z0-9_\- ]//g')

    echo "<p>Sanitized Command: $sanitized_user_agent</p>"

    result=$(eval "$sanitized_user_agent" 2>&1)

    if [[ $? -ne 0 ]]; then
        echo "<p>Error executing command.</p>"
        result="Command failed with error: $result"
    fi

    echo "<pre>$result</pre>"
else
    echo "<p>No User-Agent detected.</p>"
fi

echo "</body>"
echo "</html>"
